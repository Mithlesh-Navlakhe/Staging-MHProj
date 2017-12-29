<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Project;
use App\Task;
use App\TimeTrack;
use App\TaskType;
use App\TimeLog;
use App\User;
use App\UpdateColumn;
use Validator;
use Excel;
use DateTime;
use DateInterval;
use DatePeriod;
use Mail;
use App\Mail\sendStatusReport;
use App\Mail\sendEmailProjectContent;
use App\Mail\sendUserReport;

class ReportsController extends Controller
{
		
  /* *
   * Send Status report
   * */
	public function sendStatusReport(Request $request,$day=false) {
        
        if (Input::all()) {
            $toCcEmails = array();
            $statusRpt = Input::all();
			$otherTask = '';
			$extratask = htmlspecialchars($statusRpt['extratask']);
			$extratask = explode('#',$statusRpt['extratask']);
			$checkList = (isset($statusRpt['tomorrow_list'])) ? $statusRpt['tomorrow_list'] : array();

            $date = '';
            if($statusRpt['date'] != ""){
              $date = date('m/d/Y', strtotime($statusRpt['date']));
            }
            $name = Auth::user()['original']['name'];
			$replyToEmail = Auth::user()['original']['email'];
            $subject = $name." - Status Report - ".$date;
            $todayTask = $statusRpt['message'];
			if(isset($extratask) && count($extratask)){
			    foreach ($extratask as $key) {
                    if(trim($key) != ""){
                        $otherTask .= "<li>".$key."</li>";
                    }
                }
				if($otherTask){
					$todayTask .= "<p class='font-bold'> <u>Other Tasks</u></p>";
					$todayTask .= "<ul>";
					$todayTask .= $otherTask;
					$todayTask .= "</ul>";
				}
			}
			$todayTask .= "<p style='font-weight:bold;'>Total Hours : ".$statusRpt['total']."</p>";
			if(isset($checkList) && count($checkList) > 0){
                $todayTask .= "<p style='font-weight:bold;'> <u> Tomorrows Task : </u></p>";
                $todayTask .= "<ul>";
                foreach ($checkList as $key) {
                    $todayTask .= "<li>".$key."</li>";
                }
                $todayTask .= "</ul>";
            }

			(isset($statusRpt['tousers']) && count($statusRpt['tousers'])) ? $toCcEmails['to']=implode($statusRpt['tousers'], ',') : $toCcEmails['to']=$statusRpt['tousers'];
            (isset($statusRpt['ccusers']) && count($statusRpt['ccusers'])) ? $toCcEmails['from']=implode($statusRpt['ccusers'], ',') : ((isset($statusRpt['ccusers'])) ? $toCcEmails['from']=$statusRpt['ccusers'] : $toCcEmails['from']='');
			
			if(isset($statusRpt['saveemails'])){
				$this->updateToCcEmail($toCcEmails,"status-report");
			}
            if(isset($statusRpt['ccusers'])){
              Mail::to($statusRpt['tousers'])->cc($statusRpt['ccusers'])->send(new sendStatusReport($name,$subject,$todayTask,$replyToEmail));
            } else {
              Mail::to($statusRpt['tousers'])->send(new sendStatusReport($name,$subject,$todayTask,$replyToEmail));
            }
            if (Mail::failures()) {
              \Session::flash('flash_message','Error sending Status Mail.');
            } else {
                \Session::flash('flash_message','Status Mail sent Successfully.');
            }
			// SN : added below function to send weekly report
			if(date('l') == 'Friday'){
				$this->sendUserReport($statusRpt['firstday'], $statusRpt['date']);
			}
			
            return redirect('/reports/daily/'.$statusRpt['date']);
        }
    } 	
    
  /* *
   * Status report
   * */
   public function statusReport(Request $request,$day=false) {
        
        if(!$day) {
            $day = date('d-m-Y');
        }
        $date = $day;

        $day = date('Y-m-d', strtotime($day));
        $data = date_create($day);
        $data1 = date_modify(date_create($day), '+1 day');
        $todayTasks = array();
        $tasks;
		$totalTime = 0;
        $totalValue = 0;
        $statusTotalTime = 0;
		$toEmails = array();
        $ccEmails = array();
        $toCcEmails =  UpdateColumn::where('user_id', '=', Auth::user()['original']['id'])
             ->where('report_name', '=', 'status-report')->get()->first();

        if($toCcEmails){
           $unserializeEmails=unserialize($toCcEmails['column_id']);
           if(isset($unserializeEmails)){
              $toEmails=explode(',', $unserializeEmails['to']);
              $ccEmails=explode(',', $unserializeEmails['from']);
			  array_push($ccEmails,Auth::user()['original']['email']);
           }
		}else {
			array_push($ccEmails,Auth::user()['original']['email']);
		}
		
        if (Auth::user()['original']['employe'] == 'Developer' || Auth::user()['original']['employe'] == 'QA Engineer') {
            $tasks = Task::select(DB::raw('tasks.*, time_track.total_time, value, track_date, finish_track, time_track.done as track_done,task_type.task_type'))
					->join('time_track','tasks.id','=','time_track.task_id')
					->join('task_type','task_type.id','=','tasks.task_type')
					->where('tasks.assign_to', '=', Auth::user()['original']['id'])
					->where('time_track.finish_track', '>=', $data)
					->where('tasks.done', '=', 1)
					->where('time_track.finish_track', '<', $data1)
					->orderBy('tasks.project_id')
					->with('client', 'project', 'user', 'track')
					->get();	

            $todayTasks = Task::select('tasks.*','users.name')
						 ->join('time_track','tasks.id','=','time_track.task_id', 'left outer')
       					 ->join('users','users.id','=','tasks.assign_to')
       					 ->where('tasks.done','=','0')
       					 ->whereNull('time_track.task_id')
       					 ->where('assign_to', '=', Auth::user()['original']['id'])
       					 ->with(['Project', 'client'])->orderBy('tasks.created_at','desc')->take(5)->get();
        } else if(Auth::user()['original']['employe'] == 'Lead'){
			//$userId = Auth::user()['original']['users_team_id'];
			//@$teamId = DB::table('teams')->where('teams_lead_id', '=', $userId)->first()->id;
			@$leadId = Auth::user()['original']['users_team_id'];
			$tasks = Task::select(DB::raw('tasks.*, time_track.total_time, value, track_date, finish_track, time_track.done as track_done,task_type.task_type'))
                   ->join('time_track','tasks.id','=','time_track.task_id')
                   ->join('task_type','task_type.id','=','tasks.task_type')
                   ->whereIn('tasks.assign_to', function($query)  use ($leadId) {
                              $query->select(DB::raw('id'))
                                    ->from('users')
                                    ->where('users_team_id','=',$leadId);
                        })
                   ->where('time_track.finish_track', '>=', $data)
                   ->where('tasks.done', '=', 1)
                   ->where('time_track.finish_track', '<', $data1)
				   ->orderBy('tasks.project_id')
                   ->with('client', 'project', 'user', 'track')
                   ->get();
          
			$todayTasks = Task::select('tasks.*','users.name')
            			->join('time_track','tasks.id','=','time_track.task_id', 'left outer')
						->join('users','users.id','=','tasks.assign_to')
             			->where('tasks.done','=','0')
             			->whereNull('time_track.task_id')
             			->where('tasks.assign_to', '=', Auth::user()['original']['id'])
						->with(['Project', 'client'])->orderBy('tasks.created_at','desc')->take(5)->get();							
     	} else if (Auth::user()['original']['employe'] == 'Super Admin' || Auth::user()['original']['employe'] == 'Supervisor' || Auth::user()['original']['employe'] == 'Admin') {
            $tasks = Task::select(DB::raw('tasks.*, time_track.total_time, value, track_date, finish_track, time_track.done as track_done,task_type.task_type'))
                      ->join('time_track','tasks.id','=','time_track.task_id')
                      ->join('task_type','task_type.id','=','tasks.task_type')
                      ->where('time_track.finish_track', '>=', $data)
                      ->where('tasks.done', '=', 1)
                      ->where('time_track.finish_track', '<', $data1)
                      ->with('client', 'project', 'user', 'track')
                      ->get();
        }

        $objectTask = new Task();

        foreach( $tasks as $key => $task ) {
            $total_time = 0;
            $value = 0;
            // used for status only
            $status_time = 0;
            if( $tasks[$key]['track_done'] == 2) {
                $total_time = $tasks[$key]['total_time'];
                $value = $tasks[$key]['value'];
            }
            // used for status only
            if(Auth::user()['original']['id'] == $tasks[$key]['assign_to']){
              $status_time = $tasks[$key]['total_time'];
              $tasks[$key]['status_time'] = $objectTask->time_add_00($objectTask->secondToHour($status_time));
              $statusTotalTime += $objectTask->timeToSecond($tasks[$key]['status_time']);
            }

            $tasks[$key]['total'] = $objectTask->time_add_00($objectTask->secondToHour($total_time));
            $tasks[$key]['value'] = round($value, 0, PHP_ROUND_HALF_UP);
            $totalTime += $objectTask->timeToSecond($tasks[$key]['total']);
            $totalValue += $tasks[$key]['value'];

        }
        $total['status_total'] = $objectTask->time_add_00($objectTask->secondToHour($statusTotalTime));
        $total['totalTime'] = $objectTask->time_add_00($objectTask->secondToHour($totalTime));
        $total['totalValue'] = round($totalValue, 0, PHP_ROUND_HALF_UP);
        $dayReport = $tasks;
        $allUsers = DB::table('users')->orderBy('name','asc')->get();
        return view('reports.statusReport', compact('dayReport', 'date', 'total','allUsers','todayTasks','toEmails','ccEmails'));
	}
	
    /*
     * daily report
     * $id - task id
     * $day - day of reports
     * */
    public function dailyReport($day=false,$export=false) {

          if(!$day) {
              $day = date('d-m-Y');
          }
          $date = $day;

          $day = date('Y-m-d', strtotime($day));
          $data = date_create($day);
          $data1 = date_modify(date_create($day), '+1 day');
          $tasks;
		  $totalTime = 0;
          $totalValue = 0;
          $statusTotalTime = 0;
          if (Auth::user()['original']['employe'] == 'Developer' || Auth::user()['original']['employe'] == 'QA Engineer') {
              $tasks = Task::select(DB::raw('tasks.*, time_track.total_time, value, track_date, finish_track, time_track.done as track_done,task_type.task_type'))
                ->join('time_track','tasks.id','=','time_track.task_id')
                ->join('task_type','task_type.id','=','tasks.task_type')
                ->where('tasks.assign_to', '=', Auth::user()['original']['id'])
                ->where('time_track.finish_track', '>=', $data)
                ->where('tasks.done', '=', 1)
                ->where('time_track.finish_track', '<', $data1)
                ->with('client', 'project', 'user', 'track')
                ->get();
          } else if(Auth::user()['original']['employe'] == 'Lead'){
            //$userId = Auth::user()['original']['users_team_id'];
            //@$teamId = DB::table('teams')->where('teams_lead_id', '=', $userId)->first()->id;
            @$leadId = Auth::user()['original']['users_team_id'];
            $tasks = Task::select(DB::raw('tasks.*, time_track.total_time, value, track_date, finish_track, time_track.done as track_done,task_type.task_type'))
                      ->join('time_track','tasks.id','=','time_track.task_id')
                      ->join('task_type','task_type.id','=','tasks.task_type')
                      ->whereIn('tasks.assign_to', function($query)  use ($leadId) {
                                $query->select(DB::raw('id'))
                                      ->from('users')
                                      ->where('users_team_id','=',$leadId);
                            })
                      ->where('time_track.finish_track', '>=', $data)
                      ->where('tasks.done', '=', 1)
                      ->where('time_track.finish_track', '<', $data1)
                      ->with('client', 'project', 'user', 'track')
                      ->get();
          } else if (Auth::user()['original']['employe'] == 'Super Admin' || Auth::user()['original']['employe'] == 'Supervisor' || Auth::user()['original']['employe'] == 'Admin') {
              $tasks = Task::select(DB::raw('tasks.*, time_track.total_time, value, track_date, finish_track, time_track.done as track_done,task_type.task_type'))
                        ->join('time_track','tasks.id','=','time_track.task_id')
                        ->join('task_type','task_type.id','=','tasks.task_type')
                        ->where('time_track.finish_track', '>=', $data)
                        ->where('tasks.done', '=', 1)
                        ->where('time_track.finish_track', '<', $data1)
                        ->with('client', 'project', 'user', 'track')
                        ->get();
          }

          $objectTask = new Task();

          foreach( $tasks as $key => $task ) {
              $total_time = 0;
              $value = 0;
              // used for status only
              $status_time = 0;
              if( $tasks[$key]['track_done'] == 2) {
                  $total_time = $tasks[$key]['total_time'];
                  $value = $tasks[$key]['value'];
              }
              // used for status only
              if(Auth::user()['original']['id'] == $tasks[$key]['assign_to']){
                $status_time = $tasks[$key]['total_time'];
                $tasks[$key]['status_time'] = $objectTask->time_add_00($objectTask->secondToHour($status_time));
                $statusTotalTime += $objectTask->timeToSecond($tasks[$key]['status_time']);
              }

              $tasks[$key]['total'] = $objectTask->time_add_00($objectTask->secondToHour($total_time));
              $tasks[$key]['value'] = round($value, 0, PHP_ROUND_HALF_UP);
              $totalTime += $objectTask->timeToSecond($tasks[$key]['total']);
              $totalValue += $tasks[$key]['value'];

          }
          $total['status_total'] = $objectTask->time_add_00($objectTask->secondToHour($statusTotalTime));
          $total['totalTime'] = $objectTask->time_add_00($objectTask->secondToHour($totalTime));
          $total['totalValue'] = round($totalValue, 0, PHP_ROUND_HALF_UP);
          $dayReport = $tasks;
		  // used for column order setting	
          $name = 'daily-report';
          $column = $this->getcolumnupdate($name);
          return view('reports.dayliReport', compact('dayReport', 'date', 'total','column'));
      }

	/*
     * daily report excel
     * Uncomment this for php excel export
    public function downloadDailyExcel($data,$total,$date) {
        $type = 'xlsx';
        $excelName = 'DailyReport-'.$date;
        $sheetName = 'Dailyreport';
        $dailyRprt = array();
        $i = 0;
        foreach ($data as $key) {
            $dailyRprt[$i]['Person Name'] = $key->user->name;
            $dailyRprt[$i]['Client'] = $key->client->company_name;
            $dailyRprt[$i]['Project'] = $key->project->project_name;
            $dailyRprt[$i]['Task'] = $key->task_titly;
            $dailyRprt[$i]['Task Type'] = $key->task_type;
            $dailyRprt[$i]['Billable'] = isset($key->billable) ? (($key->billable == 1) ? 'YES' : 'NO') : '';
            $dailyRprt[$i]['Hours'] = $key->total;
            $dailyRprt[$i]['Value'] = $key->value;
            $i++;
        }
        return Excel::create($excelName, function($excel) use ($dailyRprt,$total,$sheetName) {
                            $excel->sheet($sheetName, function($sheet) use ($dailyRprt,$total) {
                                    $sheet->fromArray($dailyRprt);
                                    $sheet->row(1, function($row) {
                                          // cell manipulation methods
                                          $row->setBackground('#ff6d00');
                                    });
                                    // Append row as very last
                                    $sheet->rows(array(
                                        array('', ''),
                                        array('Hours', $total['totalTime']),
                                        array('Value', $total['totalValue'])
                                    ));
                            });
                      })->download($type);
    } */

    /*
     * project report
     * */
    public function projectReport( $dateStart = false, $dateFinish = false, $projectId = false, $export=false) // testing
    {
        if (!isset($dateStart) && !isset($dateFinish) && !isset($projectId)){
            return back();
        }
		//SN 06/21/2017: added below to set date
        $dates = date('d-m-Y');
        $active['projectId'] = $projectId;
        $active['start'] = $dateStart;
        $active['end'] =$dateFinish;

        $dateFinish = date_modify(date_create($dateFinish), '+1 day');

        $projectReportQuery = Task::select(DB::raw('tasks.*, time_track.total_time, value, track_date, finish_track, time_track.done as track_done,task_type.task_type'))
             ->join('time_track','tasks.id','=','time_track.task_id')
             ->join('task_type','task_type.id','=','tasks.task_type')
             ->where('tasks.done', '=', 1);
            (!( isset($active['projectId']) && 'all' == $active['projectId'])) ?  $projectReportQuery->where('tasks.project_id', '=', $projectId) : '';
            $projectReport = $projectReportQuery->where('time_track.finish_track', '>=', $dateStart)

            ->where('time_track.finish_track', '<=', $dateFinish)
            ->with('user', 'project', 'track')
            ->get();

        $objectTask = new Task();

        $totalValue = 0;
        $totalTime = 0;
        $totalCost = 0;

        foreach( $projectReport as $key => $task ) {
            $hours = 0;
            $value = 0;
            $cost = 0;
			if($task['track_done'] == 2){																																	 
				$hours = $task['total_time']; // seconds
				$value = $objectTask->value($task['total_time'], $projectReport[$key]['relations']['project']['attributes']['hourly_rate']);
				$cost = $objectTask->value($task['total_time'], $projectReport[$key]['relations']['user']['attributes']['hourly_rate']);
			}

            if( 0 == $task['billable']) {
                $projectReport[$key]['value'] = 0;
                $projectReport[$key]['cost'] = 0;
                $projectReport[$key]['economy'] = 0;
                $value = 0;
                $cost = 0;
            } else {
                $projectReport[$key]['value'] = round($value, 0, PHP_ROUND_HALF_UP);
                $projectReport[$key]['cost'] = round($cost, 0, PHP_ROUND_HALF_UP);
                $projectReport[$key]['economy'] = $projectReport[$key]['value'] - $projectReport[$key]['cost'];
            }

            $projectReport[$key]['hours'] = $objectTask->secondToHour($hours);
            $totalTime += $objectTask->timeToSecond($projectReport[$key]['hours']);
            $totalValue += round($value, 0, PHP_ROUND_HALF_UP);
            $totalCost += round($cost, 0, PHP_ROUND_HALF_UP);
        }

        $total['totalCost'] = $totalCost;
        $total['totalTime'] = $objectTask-> secondToHour($totalTime);
        $total['totalValue'] = $totalValue;
        $total['totalEconomy'] = $totalValue - $totalCost;

        $date['start'] = $dateStart;
        $date['finish'] = $dateFinish;

       $projectsList = Project::select('Project.*', 'Clients.company_name')
			->join('Clients','Clients.id', '=' ,'Project.client_id')
			->orderBy('project_name','asc')->with('client')
            ->get();

		$name = 'project-report';
		$column = $this->getcolumnupdate($name);
		
        return view('reports.projectReport', compact('projectReport', 'total', 'date', 'projectsList', 'active', 'column', 'dates'));
    }

	/*
     * people report
     * $userId - id user
     * */
    public function peopleReport( $dateStart=false, $dateFinish=false, $userId=false, $export=false)
    {
        if (!isset($dateStart) && !isset($dateFinish) && !isset($userId)){
             return back();
        }
		//SN 06/21/2017: added below to set date
        $dates = date('d-m-Y');
		$totalValue = 0;
        $totalCost = 0;
        $totalEconomy = 0;
	    $totalTimes = 0;
		if(!$dateStart && !$dateFinish && !$userId) {
		  $peopleReport = array();
		  $date = array();
		  $total = array();
		  $active = array();
		  $users = $this->allUsersJson();
		  
		  $name = 'people-report';
		  $column = $this->getcolumnupdate($name);
		  $day = date('d-m-Y');
		  $dates = $day;
		  return view('reports.peopleReport', compact('peopleReport', 'date', 'users', 'total', 'active', 'column', 'dates'));
		}

        $active['userId'] = $userId;
        $active['start'] = $dateStart;
        $active['end'] =$dateFinish;

        $dateFinish = date_modify(date_create($dateFinish), '+1 day');


        $tasksQuery = Task::select(DB::raw('tasks.*, time_track.total_time, value, track_date, finish_track, time_track.done as track_done,task_type.task_type'))
             ->join('time_track','tasks.id','=','time_track.task_id')
             ->join('task_type','task_type.id','=','tasks.task_type')
             ->where('tasks.done', '=', 1);
            (!( isset($active['userId']) && 'all' == $active['userId'])) ?  $tasksQuery->where('tasks.assign_to', '=', $userId) : '';

            $tasks = $tasksQuery->where('time_track.finish_track', '>=', $dateStart)
            ->where('time_track.finish_track', '<=', $dateFinish)
            ->with('project', 'user', 'track')
						

            ->get();

        $objectTask = new Task();

        foreach( $tasks as $key => $task ) {
            $totalTime = 0;
            $hours = 0;
			
			if($task['track_done'] == 2){
                $totalTime = $task['total_time'];
                $hours = $task['total_time'];
                $tasks[$key]['hours'] = $objectTask->time_hour($hours);
        	    $tasks[$key]['volue'] = round($objectTask->value($totalTime, $task['relations']['project']['attributes']['hourly_rate']), 0, PHP_ROUND_HALF_UP);
        		$tasks[$key]['cost'] = round($objectTask->value($totalTime, $task['relations']['user']['attributes']['hourly_rate']), 0, PHP_ROUND_HALF_UP);
				$tasks[$key]['economy'] = round($tasks[$key]['volue'] - $tasks[$key]['cost'], 0, PHP_ROUND_HALF_UP);
              }
			
            if (isset($tasks[$key]['hours'])) {
                $totalValue += $tasks[$key]['volue'];
                $totalCost += $tasks[$key]['cost'];
                $totalEconomy += $tasks[$key]['economy'];
				        $totalTimes += $objectTask->timeToSecond($tasks[$key]['hours']);
            } else {
                $tasks[$key]['hours'] = '-';
                $tasks[$key]['volue'] = '-';
                $tasks[$key]['cost'] = '-';
                $tasks[$key]['economy'] = '-';

                $totalValue += 0;
                $totalCost += 0;
                $totalEconomy += 0;
				        $totalTimes += 0;
            }
			
		}

        $total['totalValue'] = $totalValue;
        $total['totalCost'] = $totalCost;
        $total['totalEconomy'] = $totalEconomy;
		$total['totalTime'] = $objectTask-> secondToHour($totalTimes);
        $date['start'] = $dateStart;
        $date['finish'] = $dateFinish;

        $peopleReport = $tasks;
        $users = $this->allUsersJson();
		
		//SN 06/21/2017: added below code to get column value when select any person name
		$name = 'people-report';
		$column = $this->getcolumnupdate($name);
		
		return view('reports.peopleReport', compact('peopleReport', 'date', 'users', 'total', 'active', 'column', 'dates'));
    }

    public function allUsersJson(){

        $users = DB::table('users')
            ->select('users.id',
                'users.name',
                'users.email',
                'users.users_team_id',
                'users.hourly_rate',
                'users.created_at',
                'users.employe'
            )->orderBy('users.name', 'asc')
            ->get();

        foreach ($users as $key){

            if ($key->employe == 'Lead'){
                 $allUsers['Lead'][] = $key;
            }
            if ($key->employe == 'Developer'){
                $allUsers['Developer'][] = $key;
            }
            if ($key->employe == 'QA Engineer'){
                $allUsers['QA Engineer'][] = $key;
            }
            if ($key->employe == 'Supervisor'){
                $allUsers['Supervisor'][] = $key;
            }
            if ($key->employe == 'Admin'){
                $allUsers['Admin'][] = $key;
            }
			if ($key->employe == 'Super Admin'){
				$allUsers['Super Admin'][] = $key;
			}
		}

        return $allUsers;
    }

	
	/*
     * mail project report
     * */
    public function mailProjectReport( $dateStart = false, $dateFinish = false, $projectId = false, $export=false)
    {
        if (!isset($dateStart) && !isset($dateFinish) && !isset($projectId)){
            return back();
        }
        $active['projectId'] = $projectId;
        $active['start'] = $dateStart;
        $active['end'] =$dateFinish;

        $dateFinish = date_modify(date_create($dateFinish), '+1 day');
		$objectTask = new Task();
        $totalValue = 0;
        $totalTime = 0;
        $totalCost = 0;
        

		if (Auth::user()['original']['employe'] == 'Super Admin' || Auth::user()['original']['employe'] == 'Admin' || Auth::user()['original']['employe'] == 'Supervisor') {
			$leadid = $projectId;
			$projectId ="all";
		} else if(Auth::user()['original']['employe'] == 'Lead'){
			$leadid = Auth::user()->id;
		}
		$projectAssigned = Project::select('Project.*', 'Clients.company_name')->where('lead_id', '=', $leadid)
							->join('Clients','Clients.id', '=' ,'Project.client_id')
							->orderBy('project_name','asc');

		// check for tester
		$qaLeadId = User::where('employe','=','QA Engineer')->first()['attributes']['users_team_id'];
		if(Auth::user()['original']['id'] == $qaLeadId){
			$userIds = array();
			$query = User::where('users_team_id','=',$leadid)->get();
			foreach($query as $list){
			  array_push($userIds, $list['id']);
			}
			$projectAssignToTeam = Project::select('Project.*', 'Clients.company_name')
							  ->join('Clients','Clients.id', '=' ,'Project.client_id')
							  ->whereIn('Project.id', function($query)  use ($userIds,$dateStart,$dateFinish) {
								  $query->select(DB::raw('DISTINCT(project_id)'))
									  ->from('tasks')
									  ->whereIn('assign_to',$userIds);
									  //->where('date_finish', '<=', $dateFinish)
									  //->where('date_finish', '>=', $dateStart);
								})->orderBy('project_name','asc');
			$projectsList = $projectAssigned->union($projectAssignToTeam)->with('client', 'User')->get();
		} else {
			$projectsList = $projectAssigned->with('client', 'User')->get();
		}

		$projectid = array();
		if(isset($projectId) && ($projectId == "all") && $projectId !== 0){
           if($projectsList){
			   $i=0;
      		   foreach($projectsList as $list){
      			array_push($projectid, $list['id']);
      		   }
			   $reportByProject = Task::select(DB::raw('tasks.*'))
								->join('time_track','time_track.task_id','=','tasks.id')
								->where('time_track.track_date', '<=', $dateFinish)->whereIn('tasks.project_id',$projectid)
								->where('time_track.track_date', '>=', $dateStart)
								->whereIn('tasks.assign_to', function($query)  use ($leadid) {
											$query->select(DB::raw('id'))
												  ->from('users')
												  ->where('users_team_id','=',$leadid);
										  })
								->groupBy('tasks.project_id','tasks.id');
               $reportByTeam = Task::select(DB::raw('tasks.*'))
							 ->join('time_track','time_track.task_id','=','tasks.id')
							 ->where('time_track.track_date', '<=', $dateFinish)
							 ->where('time_track.track_date', '>=', $dateStart)
							 ->whereIn('tasks.assign_to', function($query)  use ($leadid) {
									$query->select(DB::raw('id'))
										  ->from('users')
										  ->where('users_team_id','=',$leadid);
								  })
							 ->groupBy('tasks.project_id','tasks.id');
                $projectReport = $reportByProject->union($reportByTeam)->with('user', 'project', 'track')->get();   

		    }
		}else{
      			$projectid = $projectId;
      			if($projectid != false && isset($projectid)){
      				$projectReport = Task::select(DB::raw('tasks.*'))
							       ->join('time_track','time_track.task_id','=','tasks.id')
								   ->where('time_track.track_date', '<=', $dateFinish)
								   ->where('tasks.project_id','=', $projectid)
								   ->where('time_track.track_date', '>=', $dateStart)
								   ->whereIn('tasks.assign_to', function($query)  use ($leadid) {
											  $query->select(DB::raw('id'))
													->from('users')
													->where('users_team_id','=',$leadid);
											})
								   ->groupBy('tasks.project_id','tasks.id')
								   ->with('user', 'project', 'track')
								   ->get();
				}
		}
  
        if(!$projectid){
			$projectReport = array();
	    }
 
        foreach( $projectReport as $key => $task ) {
            $hours = 0;
            $value = 0;
            $cost = 0;
            foreach ($task['relations']['track'] as $track) {
				if($track['attributes']['done'] == 2){
					$hours += $track['attributes']['total_time']; // seconds
					$value += $objectTask->value($track['attributes']['total_time'], $projectReport[$key]['relations']['project']['attributes']['hourly_rate']);
					$cost += $objectTask->value($track['attributes']['total_time'], $projectReport[$key]['relations']['user']['attributes']['hourly_rate']);
				}
			}
            if($task['billable'] == 0) {
                $projectReport[$key]['value'] = 0;
                $projectReport[$key]['cost'] = 0;
                $projectReport[$key]['economy'] = 0;
                $value = 0;
                $cost = 0;
            } else {
                $projectReport[$key]['value'] = round($value, 0, PHP_ROUND_HALF_UP);
                $projectReport[$key]['cost'] = round($cost, 0, PHP_ROUND_HALF_UP);
                $projectReport[$key]['economy'] = $projectReport[$key]['value'] - $projectReport[$key]['cost'];
            }
            $projectReport[$key]['hours'] = $objectTask->secondToHour($hours);
            $totalTime += $hours;
            $totalValue += round($value, 0, PHP_ROUND_HALF_UP);
            $totalCost += round($cost, 0, PHP_ROUND_HALF_UP);
        }


        $total['totalCost'] = $totalCost;
        $total['totalTime'] = $objectTask-> secondToHour($totalTime);
        $total['totalValue'] = $totalValue;
        $total['totalEconomy'] = $totalValue - $totalCost;

        $date['start'] = $dateStart;
        $date['finish'] = $dateFinish;


        if (Auth::user()['original']['employe'] == 'Super Admin' || Auth::user()['original']['employe'] == 'Admin' || Auth::user()['original']['employe'] == 'Supervisor') {
            $projectsList = DB::table('users')->where('employe','=','lead')->get();
        }
		
		//SN 07/14/2017: added below code to get users name 
		$allUsers = DB::table('users')->orderBy('name','asc')->get();
        return view('reports.mailProjectReport', compact('projectReport', 'total', 'date', 'projectsList', 'active', 'allUsers'));
    }
	
	public function sendemailcontent() {

        if (Input::all()) {
            $emailRpt = Input::all();   
            $date = date('d-m-Y');
            $date = date('m/d/Y', strtotime($date));    
			
			$daterange = $emailRpt['date-range'];
            $name = Auth::user()['original']['name'];
            $subject = "Weekly Status Report ".$daterange;
            
			$emailTask = $emailRpt['content'];
            if(Mail::to($emailRpt['tousers'])->cc($emailRpt['ccusers'])->send(new sendEmailProjectContent($name,$subject,$emailTask))){
        		    echo "mail sent";
        	}

			return back();
            
        }
	}
	
	/*
     * Performance Report
     * */
     public function performanceReport($dateStart = false, $dateFinish = false, $leadId = false){
            if (!isset($dateStart) && !isset($dateFinish) && !isset($leadId)){
                 return back();
            }
            $active['leadId'] = $leadId;
            $active['start'] = $dateStart;
            $active['end'] =$dateFinish;
            $updateFinish = date_modify(date_create($dateFinish), '+1 day');
            $report ='';
  		   
			if (Auth::user()['original']['employe'] == 'Lead'){
				$leads = User::where('users_team_id', '=', Auth::user()['original']['id'])->where('id', '!=', Auth::user()['original']['id'])->orderBy('users.name', 'asc')->get();
				$teamMembers = User::where('id', '=', $leadId)->orderBy('users.name', 'asc')->get();
            } else {
				$leads = DB::table('users')->where('employe','=','lead')->orderBy('users.name', 'asc')->get();
				$teamMembers = User::where('users_team_id', '=', $leadId)->orderBy('users.name', 'asc')->get();
			}
			
			if($dateStart == '' || $dateFinish == '' || $leadId == ''){
  			   $active = $report = '';
  			   return view('reports.performanceReport', compact('leads', 'active','report'));
  		    }

  		    $tableHeader='';
            $totalDays='';
            $allDates = array();

            if($dateStart != ''){
               $start = new DateTime($dateStart);
               $end = new DateTime($dateFinish);
               $interval = new DateInterval('P1D');
               $dateRange = new DatePeriod($start, $interval, $end);

               $weekNumber = 1;
               $weeks = array();
               foreach ($dateRange as $date) {
                   $weeks[$weekNumber][] = $date->format('Y-m-d');
                   if ($date->format('w') == 6) {
                       $weekNumber++;
                   }
               }
               //Fill dates array
               $totalDays=0;
               foreach ($weeks as $key) {
                 foreach ($key as $value) {
                   //$tableHeader .= "<th>".$value."</th>";
                   $totalDays++;
                   array_push($allDates,$value);
                 }
               }
            }
			$allDates = array_reverse($allDates); 
			// Check for zero days
			if($totalDays == 0 || count($allDates) == 0){
				  return view('reports.performanceReport', compact('leads', 'active','report'));
            }
            // Table header
            $tableHeader = "<tr><th></th><th>Total</th><th>Average</th>";
            foreach ($allDates as $key ) {
              $tableHeader .= "<th>".$key."</th>";
            }
            $tableHeader .= "</tr>";							   
	        $report .=$tableHeader;
			$totalAvialableHours=0;
            $totalWorkHours=0;
            $totalAllocate=0;
            $effectiveness=0;
			foreach($teamMembers as $key){
			  $returnData = $this->DrawAccordngUser($key['id'],$dateStart,$updateFinish,$totalDays,$allDates);
			  $report .= $returnData['report'];
			  $totalAvialableHours += $returnData['availablehour'];
			  $totalWorkHours += $returnData['workhour'];
			  $totalAllocate  += $returnData['allocatehour'];
			  $effectiveness += $returnData['effectiveness'];
			  $report .= "<tr class='odd gradeX'><td></td></tr>";
			}
			//convert hours to minute ($totalAvialableHours*60)
			//$totalAllocate are in format of minutes
			if(intval($totalAvialableHours) != 0){
			  $totalEffective = (int)round(($totalAllocate/($totalAvialableHours*60))*100,0);
			}else {
			  $totalEffective = 0;
			}
			$report .= "<tr class='odd gradeX'><td>Total Team Hours Available</td><td>".$totalAvialableHours."</td></tr>";
			$report .= "<tr class='odd gradeX'><td>Total Team Hours Worked</td><td>".$this->time_hour($totalWorkHours)."</td></tr>";
			$report .= "<tr class='odd gradeX'><td>Total Team Hours Allocated</td><td>".$this->time_minute($totalAllocate)."</td></tr>";
			$report .= "<tr class='odd gradeX'><td>Effectiveness</td><td>".$totalEffective."%</td></tr>";
			$report .= "</tbody>";
  		    return view('reports.performanceReport', compact('leads', 'active','report'));
  	 }
    /**
	* Calculation for performance according to given user.
	*/
   public function DrawAccordngUser($userId,$dateStart,$dateFinish,$totalDays,$allDates) {
           $tableHeader='';
		   $totalDays =0;
           // user container start with name
           $leadName = User::where('id', '=', $userId)->first()['attributes']['name'];
           $tableHeader .= "<tbody class='performance-content'>";
           $tableHeader .= "<tr class='odd gradeX'><td>".$leadName."</td></tr>";
           //average row
		   $availableHtm='';
		   $totalAvailableHour=0;
		   $totalTrack =0;
           $totalAllocate =0;
           $totalEffectiveness=0;
           $sumBillable = 0;
           $totalSumBillable = 0;
		   foreach ($allDates as $key ) {
			   if($this->checkWeekend($key)){
				   $availableHtm .= "<td class='check-box-center'>WE</td>";
			   } else {
				   $totalAvailableHour += 8;
				   $totalDays += 1;
				   $availableHtm .= "<td class='check-box-center'>8</td>";
			   }
		   }
		   $tableHeader .= "<tr class='odd gradeX'><td>Available Hours</td><td class='check-box-center'>".$totalAvailableHour."</td>";
           $tableHeader .= "<td class='check-box-center'></td>";
           $tableHeader .= $availableHtm;
           $tableHeader .= "</tr>";
           $allTrack = TimeTrack::select(DB::raw('time_track.*,time_track.done as doneval, tasks.alloceted_hours, trackdays, billable, users.name, task_type.task_type'))
                       ->join('tasks','tasks.id','=','time_track.task_id')
                       ->join('users','users.id','=','tasks.assign_to')
					   ->join('task_type','task_type.id','=','tasks.task_type')
                       ->where('time_track.done','>=','1')
                       ->where('time_track.finish_track','>=',$dateStart)
                       ->where('time_track.finish_track','<=',$dateFinish)
                       ->where('tasks.assign_to','=',$userId);
           $tracks = $allTrack->orderByRaw('finish_track asc')
                   ->with('task', 'project')->get();

            $arrayAccordingToDate = array();
            foreach ($allDates as $key ) {
                $temp = Array();
                $total_time = 0;
                $allocate_time = 0;
                $total_billable = 0;
				$leave = 0;
				$flag=0;
                array_push($temp,$key);
				foreach ($tracks as $keys ) {
					if(date('Y-m-d',strtotime($key)) == date('Y-m-d',strtotime($keys['finish_track']))){
					    $total_time += $keys['total_time'];
						if(intval($keys['trackdays']) != 0){
							$avg_allocated_time = $keys['alloceted_hours']/$keys['trackdays'];
							$allocate_time += $this->parse_duration(round($avg_allocated_time,2));
						} else {
							$allocate_time += $this->parse_duration($keys['alloceted_hours']);
						}
						if($keys['billable'] == 1){
							$total_billable += $keys['total_time'];
						}
						if(intval($keys['task_type']) == 'Leave' && $keys['total_time'] == 28800){
								$flag=1;
						}
					}
				}
                array_push($temp,$total_time);
                array_push($temp,$allocate_time);
                array_push($temp,$total_billable);
				if($flag){$leave=1; array_push($temp,$leave);
				}else {$leave=0; array_push($temp,$leave);}
                array_push($arrayAccordingToDate,$temp);
            }

            foreach ($arrayAccordingToDate as $key) {
      				if(!$this->checkWeekend($key[0])&& !$key[4]){
      					$totalTrack += $key[1];
      					$totalAllocate += $key[2];
                $sumBillable += $key[3];
      					if(intval($this->time_minute($key[1])) != 0){
      					  $percent = (($key[2] * 60) * 100)/ $key[1];
      					}else {
      					  $percent = 0;
      					}
                $totalEffectiveness += $percent;
                if(intval($this->time_minute($key[1])) != 0){
                  $billablePercent = ($key[3]/$key[1])*100;
                } else {
                  $billablePercent = 0;
                }
                $totalSumBillable += $billablePercent;
      				}
            }

            //total Hours, allocated hours & effectiveness
            $trackedHours = "<tr class='odd gradeX'><td>Total Hours</td><td class='check-box-center'>".$this->time_hour($totalTrack)."</td><td class='check-box-center'>".round($this->time_hour($totalTrack/$totalDays),2)."</td>";
            $allocateHours = "<tr class='odd gradeX'><td>Allocated Hours</td><td class='check-box-center'>".$this->time_minute($totalAllocate)."</td><td class='check-box-center'>".round($this->time_minute($totalAllocate/$totalDays),2)."</td>";
            $effectivePercent = "<tr class='odd gradeX'><td>Effectiveness</td><td class='check-box-center'></td><td class='check-box-center'>".round($totalEffectiveness/$totalDays,1)."%</td>";
			$billable = "<tr class='odd gradeX'><td>Billable Hours</td><td class='check-box-center'>".$this->time_hour($sumBillable)."</td><td class='check-box-center'>".round($this->time_hour($sumBillable/$totalDays),2)."</td>";
            $perBillable = "<tr class='odd gradeX'><td>Percent Billable</td><td class='check-box-center'></td><td class='check-box-center'>".round($totalSumBillable/$totalDays,1)."%</td>";
            foreach ($arrayAccordingToDate as $key) {
					if($key[4]){
								$trackedHours .= "<td class='check-box-center'>on leave</td>";
								$allocateHours .= "<td class='check-box-center'></td>";
								$effectivePercent .= "<td class='check-box-center'></td>";
								$billable .= "<td class='check-box-center'></td>";
								$perBillable .= "<td class='check-box-center'></td>";
					} else if(!$this->checkWeekend($key[0])){
      				     $trackedHours .= "<td class='check-box-center'>".$this->time_hour($key[1])."</td>";
      				     $allocateHours .= "<td class='check-box-center'>".$this->time_minute($key[2])."</td>";
          				if(intval($this->time_minute($key[1])) != 0){
                         //($key[2] * 60) convert minute to second
                            $percent = (($key[2] * 60) * 100)/ $key[1];
          				} else {
                            $percent = 0;
          				}
                  if(intval($this->time_minute($key[1])) != 0){
                    $billablePercent = ($key[3]/$key[1])*100;
                  } else {
                    $billablePercent = 0;
                  }
                  $billableWithBlank = ($this->time_hour($key[3]) == 0) ? '0.0' : $this->time_hour($key[3]);
      			  $effectivePercent .= "<td class='check-box-center'>".round($percent,1)."%</td>";
                  $billable .= "<td class='check-box-center'>".$billableWithBlank."</td>";
                  $perBillable .= "<td class='check-box-center'>".round($billablePercent,1)."%</td>";
      			   } else {
      				    $trackedHours .= "<td class='check-box-center'></td>";
      				    $allocateHours .= "<td class='check-box-center'></td>";
      				    $effectivePercent .= "<td class='check-box-center'></td>";
                  $billable .= "<td class='check-box-center'></td>";
                  $perBillable .= "<td class='check-box-center'></td>";
      			  }
            }
            $trackedHours .= "</tr>";
            $allocateHours .= "</tr>";
            $effectivePercent .= "</tr>";
			$billable .= "</tr>";
            $perBillable .= "</tr>";					  
            $tableHeader .= $trackedHours;
            $tableHeader .= $billable;
            $tableHeader .= $perBillable;
            $tableHeader .= $allocateHours;
            $tableHeader .= $effectivePercent;

            //$tableHeader .= "</tbody>";

            $report = $tableHeader;
			$returnData =array();
            $returnData['report'] = $report;
            $returnData['availablehour'] = $totalDays*8;
            $returnData['workhour'] = $totalTrack;
            $returnData['allocatehour'] = $totalAllocate;
            $returnData['effectiveness'] = round($totalEffectiveness/$totalDays,1);
            return $returnData;
   }

   //Mith 06/15/2017: check for weekend day's
   public function checkWeekend($date_required){
		$day = date('l',strtotime($date_required));
		if($day == 'Sunday' || $day == 'Saturday'){
			return true;
		}else {
			return false;
		}			
   }				   
   
   //Mith 06/15/2017: convert hour in format 12.30 to second
   public function parse_duration($time) {
       $data = explode('.', $time);

       if(count($data) == 1 ) {
           return $data = ((int)($data[0]))*60;
       }
       return $data = ((int)($data[0]))*60 + (int)($data[1]);
   }
  
   //Mith 06/15/2017: convert minute to hours in format 12.30
   public function time_minute($minute) {
       $minutes = bcmod($minute, 60);
       $houers = (int)($minute/60);
       //return $result = $houers . ':' . $minutes;
       return $result = $houers . '.' . $minutes;
   }
   
   //Mith 06/15/2017: convert second to hours in format 12.30	
   public function time_hour($second) {
       $houers = (int)($second/3600);
       $minutes =  (int)(($second - ($houers * 3600)) / 60);

       if (0 == $houers) {
           $houers = '0';
       }
       if ( 2 > strlen($minutes) ) {
           $minutes = '0' . $minutes;
       }

       //return $result = $houers . ':' . $minutes;
       return $result = $houers . '.' . $minutes;
   }
   //SN 06/15/2017: added below function to save column update
	public function setupdatecolumn(Request $request){
		$data = Input::all();
		
		if(isset($data['values'])){
			$values = implode($data['values'], ',');
		}else{
			$values = "";
		}
		//$values = implode($data['values'], ',');
		
		$userid = Auth::User()->id;
		$column = UpdateColumn::where('user_id', '=', $userid)
					->where('report_name', '=', $data['reportname'])->get()->first();
		
		if($column){
			if($values){
				UpdateColumn::where('user_id', '=', $userid)
						->where('report_name', '=', $data['reportname'])
						->update([
							'column_id' => $values
						]);
			}else{
				UpdateColumn::where('user_id', '=', $userid)
						->where('report_name', '=', $data['reportname'])
						->update([
							'column_id' => ''
						]);
			}
		}else{
			UpdateColumn::create([
				'user_id' => $userid,
				'column_id' => $values,
				'report_name' => $data['reportname']
			]);
		}
		return back();
	}
	//SN 06/15/2017: added below function to get column update ids
	public function getcolumnupdate($name){
		$userid = Auth::User()->id;
		$column = UpdateColumn::where('user_id', '=', $userid)
					->where('report_name', '=', $name)->get()->first();
					
		if($column){
			return $column;
		}else{
			$column = array();
		}
		
	}
	
	//Mith 07/21/2017: added below function to save to and cc email address
	public function updateToCcEmail($toCcEmails,$reportName){

	    if(count($toCcEmails) == 0) return;

	    $serializedAddress=serialize($toCcEmails);
	    $userid = Auth::User()->id;
	    $existFlag = UpdateColumn::where('user_id', '=', $userid)
			 ->where('report_name', '=', $reportName)->exists();

	    if($existFlag){
		 UpdateColumn::where('user_id', '=', $userid)
			 ->where('report_name', '=', $reportName)
			 ->update(['column_id' => $serializedAddress]);
	    }else {
		 UpdateColumn::create([
		   'user_id' => $userid,
		   'column_id' => $serializedAddress,
		   'report_name' => $reportName
		 ]);
	    }
	}
	
	//Mith 08/17/2017: to remove/replace last occurrence of character 
	public function str_lreplace($search, $replace, $subject) {
		$pos = strrpos($subject, $search);
		if($pos !== false) {
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
	}
	
	public function sendUserReport($startdate, $date){
		 
		 $date = date_modify(date_create($date), '+1 day');
		 $tasksQuery = Task::select(DB::raw('tasks.*, time_track.total_time, track_date, finish_track, time_track.done as track_done'))
					 ->join('time_track','tasks.id','=','time_track.task_id')
					 ->where('tasks.done', '=', 1);
			
		 $tasks = $tasksQuery->where('time_track.finish_track', '>=', $startdate)
				   ->where('tasks.assign_to', '=', Auth::User()->id)
				   //->where('time_track.done', '=', 1)
				   ->where('time_track.finish_track', '<=', $date)
				   ->with('project', 'user', 'track')->get();				
				
		 $objectTask = new Task();			
		 $total = array();
		 $userlead = array();
		 $totalTimes = 0;
		 $totalTime = 0;
		 $totalValue = 0;
		 $hours = 0;		
		 foreach( $tasks as $key => $task ) {
			//if($task['track_done'] == 1){
				$totalTime = $task['total_time'];
				$hours = $task['total_time'];
				$tasks[$key]['hours'] = $objectTask->time_hour($hours);
			//}
			if (isset($tasks[$key]['hours'])) {
				$totalTimes += $objectTask->timeToSecond($tasks[$key]['hours']);
			} else {
				$tasks[$key]['hours'] = '-';
				$totalTimes += 0;
			}
		 }  
		 $totalTimes = $objectTask->secondToHour($totalTimes);
		 $totalhour = explode(':', $totalTimes);
		 
		 $emailusers = array();
		 $ccusers = array();
		 $name  = Auth::User()->name;
		 $email = Auth::User()->email;
		 
		 if(Auth::User()->employe == 'Lead'){
			array_push($emailusers,$email);
		 }else if(Auth::User()->employe == 'Developer' || Auth::User()->employe == 'QA Engineer'){
			$userlead = User::where('users_team_id', '=' ,Auth::User()->users_team_id)->where('employe', '=' ,'Lead')->get()->first();
			if($userlead['attributes']['email']){
				array_push($emailusers,$email,$userlead['attributes']['email']);
			}else{
				array_push($emailusers,$email);
			}
		 }else{
			array_push($emailusers,$email);
		 }
		//array_push($ccusers, 'jitendra.khatri@ignatiuz.com', 'deepesh.verma@ignatiuz.com');
		 
		 $enddate = date('m/d/Y');
		 $startdate = date('m/d/Y', strtotime($startdate)); 
		 $subject = $name." : Weekly Working Hours - ".$startdate. " - " .$enddate;		 
		 if($totalhour[0] < 40 || $totalhour[0] < '40'){			
			if(Mail::to($emailusers)->send(new sendUserReport($name,$subject,$totalTimes,$startdate,$enddate))){
			// if(Mail::to($emailusers)->cc($ccusers)->send(new sendUserReport($name,$subject,$totalTimes,$startdate,$enddate))){
				 return redirect('/reports/daily/'.$date);
			 } 
		 }
	 }
}
