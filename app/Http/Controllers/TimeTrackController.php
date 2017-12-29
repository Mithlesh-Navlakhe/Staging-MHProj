<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 180);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Project;
use App\Task;
use App\TimeTrack;
use App\TaskStatus;
use App\TaskType;
use App\TimeLog;
use App\User;
use Validator;
use Mail;
use App\Mail\sendStatusMail;

class TimeTrackController extends Controller
{
    /*
     * permission for trecking time
     * */
    protected $users = [
        'Lead',
        'QA Engineer',
        'Developer'

    ];

    /**
     * @var array
     */
    protected $user = [
        'Lead',
        'Developer'
    ];

    /*
     * trecing time action
     * */
    public function trecking(Request $request, $date=false)
    {
        $task = new Task();
		$taskstatus = array();
		$timespend = array();
		$totaltracktime = '';
		$logTrackActiveLogId='';

        if (!$date) {
            if (isset($_COOKIE['SetDateTracking'])){
                $date = $_COOKIE['SetDateTracking'];
            } else {
                $date = date('d-m-Y');
            }
        }
        setcookie('SetDateTracking', $date, time() + (86400 * 30), "/");
		
		// Mith 11/28/2017: added below query to get current user tracking task time log info.
        $runningTask = TimeTrack::select(DB::raw('time_log.*'))
              ->join('tasks','tasks.id','=','time_track.task_id')
              ->join('time_log','time_log.track_id','=','time_track.id')
              ->where('tasks.assign_to', '=', Auth::user()['original']['id'])
              ->where('time_track.track_date', '=',date('Y-m-d', strtotime($date)))
              ->where('time_log.task_status','=',1)
              ->whereNull('time_log.finish')
              ->first();
		
		// SN 09/08/2017: added below query for get login users menber list and  task type to add leave
		$tasktype = TaskType::where('task_type', '=', 'Leave')->get()->first();
		$clientnproj = Project::where('project_name', '=', 'Leave')->get()->first();
		if(Auth::User()->employe == 'Lead' || Auth::User()->employe == 'Developer' || Auth::User()->employe == 'QA Engineer'){
			$userlist = User::where('users_team_id', '=', Auth::User()->users_team_id)->orderBy('name','asc')->get();
		}else if(Auth::User()->employe == 'Supervisor'){
			$userlist = User::where('id', '=', Auth::User()->id)->orderBy('name','asc')->get();
		}else if(Auth::User()->employe == 'Super Admin' || Auth::User()->employe == 'Admin'){
			$userlist = User::orderBy('name','asc')->get();
		}
		
        if( Input::all()) {
            $this->validation_track($request);
            $data = Input::all();
			if(isset($data['date_start']) && isset($data['date_finish'])) {
                if ($data['date_start'] != '' && $data['date_finish'] != '') {
					$data['date_start'] = $task->time_parser_from_js($data['date_start']);
                    $data['date_finish'] = $task->time_parser_from_js($data['date_finish']);
                } elseif ($data['date_start'] == '' && $data['date_finish'] == '') {
                    unset($data['date_start']);
                    unset($data['date_finish']);
                }
            }
            if (isset($data['additional_cost'])) {
                if ($data['additional_cost'] == '') {
                    $data['additional_cost'] = 0;
                }
            }
            if( isset( $data['duration'] ) ){
                $data['duration'] = $task->parse_duration($data['duration']);
            }
            if( $data['date_duration'] != '' ){
                $data['duration'] = $task->parse_duration($data['date_duration']);
            }
            $data['track_date'] = date('Y-m-d', strtotime($data['track_date']));
			//Mith 29/06/2017 : check task have enter for given date.
			$checkTask = TimeTrack::where('task_id','=',$data['task_id'])->where('track_date','=',$data['track_date'])->first();

            if($checkTask === null){
              TimeTrack::create( $data );
			  // Mith 29/06/2017 : increase count from 1 each time, new task tracking is started.
              DB::table('tasks')->whereId($data['task_id'])->increment('trackdays');
            } else {
              $taskTitle = Task::where('id','=',$data['task_id'])->first()['attributes']['task_titly'];
              if (strlen($taskTitle) > 70) {
          			$taskTitle = substr($taskTitle, 0, 70) . '...';
          		}
              \Session::flash('flash_message','You can\'t add same task('.$taskTitle.') for tracking.');
            } 
            return redirect('/tracking');
        }
        //Mith: 04/27/2017: added below check to filter track according lead.
        if(Auth::user()['original']['employe'] == 'Lead'){
            @$teamId = User::where('id', '=', Auth::user()->id)->first()['attributes']['users_team_id'];
			//Mith 05/05/2017: fetch task according to lead team members.
            $tracksUsers = TimeTrack::select('time_track.*', 'users.name', 'tasks.task_type','tasks.assign_to')
						  ->join('tasks','tasks.id','=','time_track.task_id')
          				  ->join('users','users.id','=','tasks.assign_to')
          				  ->where('time_track.track_date', '=', date('Y-m-d', strtotime($date)))
          				  ->where('time_track.done', '<', '2')
						  //->where('tasks.task_assign_by', '=', Auth::User()->id)
						  ->whereIn('tasks.assign_to', function($query)  use ($teamId) {
                                       $query->select(DB::raw('id'))
                                             ->from('users')
                                             ->where('users_team_id','=',$teamId);
                          });   
            //Mith 05/05/2017: fetch task according to projects assign to lead.
            $tracksProjs = TimeTrack::select('time_track.*', 'users.name', 'tasks.task_type','tasks.assign_to')
                          ->join('tasks','tasks.id','=','time_track.task_id')
          				  ->join('users','users.id','=','tasks.assign_to')
          				  ->where('time_track.track_date', '=', date('Y-m-d', strtotime($date)))
          				  ->where('time_track.done', '<', '2')
						  //->where('tasks.task_assign_by', '=', Auth::User()->id)
                          ->whereIn('tasks.project_id', function($projquery) use ($teamId) {
                                        $projquery->select(DB::raw('id'))
                                                  ->from('Project')
                                                  ->where('lead_id','=',$teamId);
                          });
			
            $tracks = $tracksUsers->union($tracksProjs)->with('task', 'project', 'timeLog')->get(); 
			
			//Mith 05/26/2017: Fetch previous track record of task.
            foreach ($tracks as $key) {
				$previousSpendTime = TimeTrack::where('task_id', '=', $key->task_id)->where('track_date', '<', date('Y-m-d', strtotime($date)))->get();
				foreach ($previousSpendTime as $keyvalue) {
					  $keys =array();
					  $keys['task_id'] = $keyvalue->task_id;
					  $keys['total_time'] = $keyvalue->total_time;
					  $keys['track_id'] = $keyvalue->id;
					  array_push($timespend,$keys);
				}
				if($key->done < 2 && $key->assign_to == Auth::User()->id){
					if(isset($key->total_time)){
						$totaltracktime += $key->total_time;
					}							
				}
			}		
        } else {
			$tracks = TimeTrack::select('time_track.*', 'users.name', 'tasks.task_type','tasks.assign_to')->with('task', 'project', 'timeLog')
					->where('track_date', '=', date('Y-m-d', strtotime($date)))
					->where('time_track.done', '<', '2')
					->join('tasks','tasks.id','=','time_track.task_id')
					->join('users','users.id','=','tasks.assign_to')
					->get();
		    //Mith 05/26/2017 fetch previous track record of task.
		    foreach ($tracks as $key) {
			    $previousSpendTime = TimeTrack::where('task_id', '=', $key->task_id)->where('track_date', '<', date('Y-m-d', strtotime($date)))->get();
			    foreach ($previousSpendTime as $keyvalue) {
					$keys =array();
					$keys['task_id'] = $keyvalue->task_id;
					$keys['total_time'] = $keyvalue->total_time;
					$keys['track_id'] = $keyvalue->id;
					array_push($timespend,$keys);
			    }
				//SN 10/06/2017: added below code to get total working hour for login user.
				if($key->done < 2 && $key->assign_to == Auth::User()->id){	
					if(isset($key->total_time)){
						$totaltracktime += $key->total_time;
					}							
			    }		
		    }
		}
		$totaltracktime = $task->time_add_00($task->secondToHour($totaltracktime));
																 
        //SN 05/05/2017
        $projects = Project::with('task','client')->get();
		/*$projects = Project::select('Project.*', 'clients.company_name')->with('task','client')
					->join('clients','clients.id', '=', 'Project.client_id')
					->get();
		*/
        if( in_array(Auth::user()->employe, $this->user ) ) {
			//MTN 05/05/2017 updated below code to get client name with project name
			$tasks;
			if(Auth::user()->employe == 'Lead'){
				$leadId = Auth::user()->id;
			    $tasks = Project::whereIn('id', function($query) use ($leadId) {
												$query->select(DB::raw('DISTINCT(project_id)'))
													  ->from('tasks')
													  ->where('assign_to', '=', $leadId);
						 					   })->with('client', 'task', 'track', 'track_log')->orderBy('project_name','asc')->get();
			} else {
			    $developerId = Auth::user()->id;
			    $tasks = Project::whereIn('id', function($query)  use ($developerId) {
															 $query->select(DB::raw('DISTINCT(project_id)'))
																   ->from('tasks')
																   ->where('assign_to','=',$developerId);
														 })->with('client', 'task', 'track', 'track_log')->orderBy('project_name','asc')->get();
			}		
            
			$tasks = $task->time_counter($tasks);
      		$taskstatus = $this->allTaskStatus();
      		
            return view('time_track.timeTraking', compact('tasks', 'date', 'tracks', 'timeLog', 'projects', 'taskstatus','timespend', 'userlist', 'tasktype', 'clientnproj', 'totaltracktime', 'runningTask'));

        }  
		if ( Auth::user()->employe == 'Super Admin' || Auth::user()->employe == 'Admin' || Auth::user()->employe == 'Supervisor' || Auth::user()->employe == 'QA Engineer' ) {
            //SN 05/05/2017 updated below code to get client name with project name
			//$tasks = Project::with('task', 'track', 'track_log')->get();
			$tasks = Project::select('Project.*')->with('task', 'track', 'track_log', 'client')->orderBy('project_name','asc')->get();
			$tasks = $task->time_counter($tasks);
			$taskstatus = $this->allTaskStatus();

            return view('time_track.timeTraking', compact('tasks', 'date', 'tracks', 'timeLog', 'projects', 'taskstatus','timespend', 'userlist', 'tasktype', 'clientnproj', 'totaltracktime', 'runningTask'));
        }
        return redirect('/');
    }
	
	/* *
     * All task type in asending order
     * */
    public function allTaskStatus(){
		$status = TaskStatus::orderBy('status_order','asc')->get();
		$taskstatus = array();
		$i=0;
		foreach($status as $sts){
				$taskstatus[$i]['id'] = $sts->id;
				$taskstatus[$i]['name'] = $sts->name;
				$taskstatus[$i]['description'] = $sts->description;
				$taskstatus[$i]['status_order'] = $sts->status_order;
				$i++;
		}
		return $taskstatus;
	}
	
    /*
     * update track log
     * */
    public function update_track( Request $request, $track_id, $date = false )
    {
        $task = new Task();
		$taskstatus = array();
		$timespend = array();
		$totaltracktime = '';				   

        if (!$date) {
            if (isset($_COOKIE['SetDateTracking'])){
                $date = $_COOKIE['SetDateTracking'];
            } else {
                $date = date('d-m-Y');
            }
        }
        setcookie('SetDateTracking', $date, time() + (86400 * 30), "/");
		$tasktype = TaskType::where('task_type', '=', 'Leave')->get()->first();
		$clientnproj = Project::where('project_name', '=', 'Leave')->get()->first();
		if(Auth::User()->employe == 'Lead' || Auth::User()->employe == 'Developer' || Auth::User()->employe == 'QA Engineer'){
			$userlist = User::where('users_team_id', '=', Auth::User()->users_team_id)->orderBy('name','asc')->get();
		}else if(Auth::User()->employe == 'Supervisor'){
			$userlist = User::where('id', '=', Auth::User()->id)->orderBy('name','asc')->get();
		}else if(Auth::User()->employe == 'Super Admin' || Auth::User()->employe == 'Admin'){
			$userlist = User::orderBy('name','asc')->get();
		}

        //Mith: 04/07/2017: added below check to filter track according lead.
		if(Auth::user()['original']['employe'] == 'Lead'){
            @$teamId = User::where('id', '=', Auth::user()->id)->first()['attributes']['users_team_id'];
            //Mith 05/05/2017: fetch task according to lead team members.
            $tracksUsers = TimeTrack::select('time_track.*', 'users.name', 'tasks.task_type', 'tasks.assign_to')
						  ->join('tasks','tasks.id','=','time_track.task_id')
          				  ->join('users','users.id','=','tasks.assign_to')
          				  ->where('time_track.track_date', '=', date('Y-m-d', strtotime($date)))
          				  ->where('time_track.done', '<', '2')
                          ->whereIn('tasks.assign_to', function($query)  use ($teamId) {
                                       $query->select(DB::raw('id'))
                                             ->from('users')
                                             ->where('users_team_id','=',$teamId);
                          });
            //Mith 05/05/2017: fetch task according to projects assign to lead.
            $tracksProjs = TimeTrack::select('time_track.*', 'users.name', 'tasks.task_type', 'tasks.assign_to')
                          ->join('tasks','tasks.id','=','time_track.task_id')
          				  ->join('users','users.id','=','tasks.assign_to')
          				  ->where('time_track.track_date', '=', date('Y-m-d', strtotime($date)))
          				  ->where('time_track.done', '<', '2')
                          ->whereIn('tasks.project_id', function($projquery) use ($teamId) {
                                        $projquery->select(DB::raw('id'))
                                                  ->from('Project')
                                                  ->where('lead_id','=',$teamId);
                          });
            $tracks = $tracksUsers->union($tracksProjs)->with('task', 'project', 'timeLog')->get();
			//Mith 05/26/2017: Fetch previous track record of task.
            foreach ($tracks as $key) {
				$previousSpendTime = TimeTrack::where('task_id', '=', $key->task_id)->where('track_date', '<', date('Y-m-d', strtotime($date)))->get();
				foreach ($previousSpendTime as $keyvalue) {
					$keys =array();
					$keys['task_id'] = $keyvalue->task_id;
					$keys['total_time'] = $keyvalue->total_time;
					$keys['track_id'] = $keyvalue->id;
					array_push($timespend,$keys);
				}
				//SN 10/06/2017: added below code to get total working hour for login user.
				if($key->done < 2 && $key->assign_to == Auth::User()->id){
					if(isset($key->total_time)){
						$totaltracktime += $key->total_time;
					}							
				}																 
			}
        } else {
			$tracks = TimeTrack::select('time_track.*', 'users.name', 'tasks.assign_to')->with('task', 'project', 'timeLog')
                   ->where('track_date', '=', date('Y-m-d', strtotime($date)))
                   ->where('time_track.done', '<', '2')
			       ->join('tasks','tasks.id','=','time_track.task_id')
			       ->join('users','users.id','=','tasks.assign_to')
                   ->get();
		    //Mith 05/26/2017 Fetch track previous track record of task.
		    foreach ($tracks as $key) {
				$previousSpendTime = TimeTrack::where('task_id', '=', $key->task_id)->where('track_date', '<', date('Y-m-d', strtotime($date)))->get();
				foreach ($previousSpendTime as $keyvalue) {
					$keys =array();
					$keys['task_id'] = $keyvalue->task_id;
					$keys['total_time'] = $keyvalue->total_time;
					$keys['track_id'] = $keyvalue->id;
					array_push($timespend,$keys);
				}
				//SN 10/06/2017: added below code to get total working hour for login user.
				if($key->done < 2 && $key->assign_to == Auth::User()->id){	
					if(isset($key->total_time)){
						$totaltracktime += $key->total_time;
					}							
			   }											 
		    }
        }
		$totaltracktime = $task->time_add_00($task->secondToHour($totaltracktime));

        if( Input::all()) {
            $this->validation_track($request);
            $data = Input::all();

            IF (ISSET($data['date_start']) && ISSET($data['date_finish'])) {
                if ($data['date_start'] != '' && $data['date_finish'] != '') {
                    $data['date_start'] = $task->time_parser_from_js($data['date_start']);
                    $data['date_finish'] = $task->time_parser_from_js($data['date_finish']);
                } elseif ($data['date_start'] == '' && $data['date_finish'] == '') {
                    unset($data['date_start']);
                    unset($data['date_finish']);
                }
            }

            IF (ISSET($data['additional_cost'])) {
                if ($data['additional_cost'] == '') {
                    $data['additional_cost'] = 0;
                }
            }

            if( isset( $data['duration'] ) ){
                $data['duration'] = $task->parse_duration($data['duration']);
            }
            if( $data['date_duration'] != '' ){
                $data['duration'] = $task->parse_duration($data['date_duration']);
            }
			/**
			 * Mith: 08/22/2017 to edit track time on task.
			 * First check given update time is less than previous tracked time.
			 * Then minus given time with all entry in timelog table untill remainingtime become negative.
			 */
			if(isset( $data['totaltime'] ) && $data['totaltime'] != '' && $data['totaltime'] != ''){
              $current_track = $task->timeToSecond($data['totaltime']);
              $track_to_check = TimeTrack::where('id', '=', $track_id)->get();
              $previous_track = $task->timeToSecond($task->time_add_00($task->time_hour($track_to_check[0]['total_time'])));
              if($current_track < $previous_track){
                $track_log = TimeLog::where('track_id','=',$track_id)->get();
                $remainingTime = $previous_track - $current_track;
                foreach ($track_log as $key) {
                  $remainingTime = $remainingTime - $key['total_time'];
                  if($remainingTime <= 0 ){
                     TimeLog::where('id','=',$key['id'])->update(['total_time' => abs($remainingTime)]);
                     break;
                  }else {
                     TimeLog::where('id','=',$key['id'])->update(['total_time' => 0]);
                  }
                }
                $data['total_time'] = $current_track;
				$this->sendMailAfterTrackEdit($track_id,$task->time_add_00($task->time_hour($track_to_check[0]['total_time'])),$data['totaltime']);
              }
            }
            $data['track_date'] = date('Y-m-d', strtotime($data['track_date']));
            unset($data['_token']);
            unset($data['date_duration']);
            unset($data['nextDate']);
			//unset($data['totaltime']);
            unset($data['actualtotaltime']);

            if (!isset($data['billable_time'])){
                $data['billable_time'] = '0';
            }
			
			//SN 09/25/2017: updated below code to update specific leave task and added task-type as per need to check
			if($tasktype['id'] == (int)$data['task_type_id']){
				$t='';
				
				if(Auth::User()->employe == 'Lead'){
					$data['totaltime'] = $task->parse_duration($data['totaltime']);
					if($data['totaltime'] <> $data['duration']){
						\Session::flash('flash_message','Change Hours and Tracked Hours should be same.');
						return redirect('/track/update/'.$track_id);
					}
				}
				
				$hr = explode(':', $data['duration']);     
				if(array_key_exists(1, $hr) == true){
					if(strlen($hr[1]) == 1){
						$hr[1] = $hr[1].'0';
					}
					if($hr[1]){
						$totaltrack = (int)$hr[0] * 60 + (int)$hr[1];
						$totallog = (int)$hr[0] * 60 * 60 + (int)$hr[1] * 60; 
						$t = $hr[0].' hour '.$hr[1].' minutes';
					}else{
						$totaltrack = (int)$hr[0] * 60;
						$totallog = (int)$hr[0] * 60 * 60; 
						$t = $hr[0].' hour';
					}
				}else{
					$totaltrack = (int)$hr[0] * 60;
					$totallog = (int)$hr[0] * 60 * 60;
					$t = $hr[0].' hour';
				}	
				$d = Input::all()['duration'];
				$dur = str_replace(':', '.', $d);
				$finishdate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
				$finish = date('Y-m-d H:i:s',strtotime($t,strtotime($finishdate)));			
				Task::where('tasks.id', '=', $data['task_id'])->where('tasks.task_type', '=', $tasktype['id'])
						->update([ 'alloceted_hours' => $dur ]);
				TimeTrack::where('id', '=', $track_id)
						  ->update(['duration' => $data['duration'], 
									'total_time' => $totaltrack
						  ]);
				TimeLog::where('track_id', '=', $track_id)
						  ->update(['finish' => $finish, 
									'total_time' => $totaltrack
						  ]);
				
				
				return redirect('/track/all');
			}else{
				unset($data['task_type_id']);
				unset($data['totaltime']);
				TimeTrack::where('id', '=', $track_id)->update( $data );
				if (isset($_COOKIE['SetDateTracking'])){
					return redirect('/tracking/' . $_COOKIE['SetDateTracking']);
				}
				return redirect('/tracking/');
			}
        }

        if( in_array(Auth::user()->employe, $this->users ) ) {
            //SN 05/05/2017: updated below code to get client name with project name
			if(Auth::user()->employe == 'Lead'){
				$leadId = Auth::user()->id;
			    $tasks = Project::whereIn('id', function($query) use ($leadId) {
												$query->select(DB::raw('DISTINCT(project_id)'))
													  ->from('tasks')
													  ->whereIn('assign_to', function($inQuery)  use ($leadId) {
																		   $inQuery->select(DB::raw('id'))
																				 ->from('users')
																				 ->where('users_team_id','=',$leadId);
														});
											   })->with('client', 'task', 'track', 'track_log')->get();
			} else {
			   $developerId = Auth::user()->id;
			   $tasks = Project::whereIn('id', function($query)  use ($developerId) {
															 $query->select(DB::raw('DISTINCT(project_id)'))
																   ->from('tasks')
																   ->where('assign_to','=',$developerId);
														 })->with('client', 'task', 'track', 'track_log')->orderBy('project_name','asc')->get();
			}

            $tasks = $task->time_counter($tasks);
            $track = TimeTrack::where('id', '=', $track_id)
					->with('project', 'task')
					->get();

            $track[0]['duration'] = $task->time_add_00($task->time_minute($track[0]['attributes']['duration']));
			$track[0]['total_time'] = $task->time_add_00($task->time_hour($track[0]['attributes']['total_time']));
			
			if (!$track[0]['attributes']['date_start'] == null) {
				$data['start'] = $task->dateParse($track[0]['attributes']['date_start']);
				$data['finish'] = $task->dateParse($track[0]['attributes']['date_finish']);
			}
			$taskstatus = $this->allTaskStatus();
			
			return view('time_track.timeTraking', compact('tasks', 'track', 'date', 'tracks', 'data' , 'taskstatus','timespend', 'userlist', 'tasktype', 'clientnproj', 'totaltracktime'));
		}
		if (Auth::user()->employe == 'Super Admin' || Auth::user()->employe == 'Admin' || Auth::user()->employe == 'Supervisor' ) {
            //SN 04/25/2017
			//$tasks = Project::with('task', 'track', 'track_log')->get();
            $tasks = Project::select('Project.*')->with('task', 'track', 'track_log', 'client')->orderBy('project_name','asc')->get();
				
			$tasks = $task->time_counter($tasks);
            $track = TimeTrack::where('id', '=', $track_id)
                ->with('project', 'task')
                ->get();

            $track[0]['duration'] = $task->time_add_00($task->time_minute($track[0]['attributes']['duration']));
            if (!$track[0]['attributes']['date_start'] == null) {
                $data['start'] = $task->dateParse($track[0]['attributes']['date_start']);
                $data['finish'] = $task->dateParse($track[0]['attributes']['date_finish']);
            }
			$taskstatus = $this->allTaskStatus();

            return view('time_track.timeTraking', compact('tasks', 'track', 'date', 'tracks', 'data', 'taskstatus','timespend', 'userlist', 'tasktype', 'clientnproj', 'totaltracktime'));
        }

        return redirect('/');
    }
	
	/*
     * approve trask
     * */
    public function sendMailAfterTrackEdit( $id ,$previous, $current ) {
		//Mith 08/02/2017: Uncomment below code to start task approval email sending functionality.
		
		$tracks = TimeTrack::where('id', '=' , $id)->get()->first();
		$task = Task::where('id', '=', $tracks['attributes']['task_id'])->get()->first();
		$user = User::where('id', '=' ,$task['attributes']['assign_to'])->get()->first();
		$email = $user['attributes']['email'];
		$name  = $user['attributes']['name'];
		$task_detail = $task['attributes']['task_titly'];

		if($task['attributes']['task_description']){
			$description = $task['attributes']['task_description'];
		}else{
			$description = '';
		}
		if(Auth::User()->name){
			$lead = Auth::User()->name;
		}else{
			$lead = '';
		}
		//Mith 05/01/2017: updated below line of code to limit email subject.
		if (strlen($task_detail) > 78) {
			$cropStr = substr($task_detail, 0, 78) . '...';
			$subject = "Track Edited : ".$cropStr;
		} else {
			$subject = "Track Edited : ".$task_detail;
		}
		$status = "Track Edited From ".$previous." To ".$current;
		$comments = "";

		//TimeTrack::where('id', '=', $id)->update([ 'done' => 2 ]);

		if(Mail::to($email)->queue(new sendStatusMail($name,$status,$subject,$task_detail,$description,$lead,$comments))){
			echo "mail sent";
		} 
		//TimeTrack::where('id', '=', $id)->update([ 'done' => 2 ]);
        return back();
    }

    /*
     * delete time track
     * */
    public function delete_track($id)
    {
        TimeTrack::where('id', '=', $id)->delete();

        return back();
    }

    /*
     * return all track
     * */
    public function all_track()
    {
		//Mith: 04/06/2017: added below check to filter track according lead,admin and other users.
        $tracks = array();
		//SN 06/21/2017: added below to set date at time track page
        $date = date('d-m-Y');
		$tasktype = TaskType::where('task_type', '=', 'Leave')->get()->first();   
		$trueval = array();
        if (Auth::user()['original']['employe'] == 'Developer' || Auth::user()['original']['employe'] == 'QA Engineer') {
            $tracks = TimeTrack::select('time_track.*','users.name','time_log.track_id','tasks.task_type')
                    ->join('tasks','tasks.id','=','time_track.task_id')
					->join('users','users.id','=','tasks.assign_to')
					->join('time_log','time_log.track_id','=','time_track.id')
                    ->where('tasks.assign_to','=',Auth::user()['original']['id'])
					->where('time_track.done','<=','1')
					->orderByRaw('time_track.done asc')
                    ->with('task', 'project', 'timeLog')->get();
					
			foreach($tracks as $key => $tr){   
				$totaltime = $tr['relations']['timeLog'];	
				foreach($totaltime as $key => $total){		
					$totallog = $total['attributes']['total_time'];
					$trackid = $total['attributes']['track_id'];    
					if(($totallog >= 10800 || $totallog >= '10800') && ($trackid == $tr['track_id']) && ($tr['task_type'] <> (int)$tasktype['attributes']['id'])){
						array_push($trueval,$tr['track_id']);
					}
				} 
			}
        } else if(Auth::user()['original']['employe'] == 'Lead'){
            @$leadId = User::where('id', '=', Auth::user()->id)->first()['attributes']['users_team_id'];
            $trackUser = TimeTrack::select(DB::raw('time_track.*,time_track.done as doneval, users.name ,time_log.track_id' ,'tasks.task_type'))
                        ->join('tasks','tasks.id','=','time_track.task_id')
                        ->join('users','users.id','=','tasks.assign_to')
						->join('time_log','time_log.track_id','=','time_track.id')
						->where('time_track.done','<=','1')
                        ->whereIn('tasks.assign_to', function($query)  use ($leadId) {
									$query->select(DB::raw('id'))
											->from('users')
                                            ->where('users_team_id','=',$leadId);
                        });
			$trackProject = TimeTrack::select(DB::raw('time_track.*,time_track.done as doneval, users.name, time_log.track_id','tasks.task_type'))
                            ->join('tasks','tasks.id','=','time_track.task_id')
							->join('users','users.id','=','tasks.assign_to')
							->join('time_log','time_log.track_id','=','time_track.id')
							->where('time_track.done','<=','1')
							->whereIn('tasks.project_id', function($projquery) use ($leadId) {
                                            $projquery->select(DB::raw('id'))
                                                      ->from('Project')
                                                      ->where('lead_id','=',$leadId);
                            });


			
			$tracks = $trackUser->union($trackProject)->orderByRaw('doneval=1 desc, doneval asc')->with('task', 'project', 'timeLog')->get();
			foreach($tracks as $key => $tr){  
				if(!$tr['task_type']){
					$tr['task_type'] = $tr['relations']['task']['attributes']['task_type'];
				}
				$totaltime = $tr['relations']['timeLog'];	
				foreach($totaltime as $key => $total){		
					$totallog = $total['attributes']['total_time'];
					$trackid = $total['attributes']['track_id'];    
					if(($totallog >= 10800 || $totallog >= '10800') && ($trackid == $tr['track_id']) && ($tr['task_type'] <> $tasktype['attributes']['id'])){
						array_push($trueval,$tr['track_id']);
					}
				} 
			}  
        } else if (Auth::user()['original']['employe'] == 'Super Admin' || Auth::user()['original']['employe'] == 'Supervisor' || Auth::user()['original']['employe'] == 'Admin') {
            $tracks = TimeTrack::select(DB::raw('time_track.*, time_track.done as doneval, users.name','time_log.track_id','tasks.task_type'))
                      ->join('tasks','tasks.id','=','time_track.task_id')
                      ->join('users','users.id','=','tasks.assign_to')
					  ->join('time_log','time_log.track_id','=','time_track.id')
					  ->where('time_track.done','<=','1')
                      ->with('task', 'project', 'timeLog')
                      ->orderByRaw('time_track.done=1 desc, time_track.done asc')
                      ->get();
			
			//SN 09/18/2017: added below code to check duplicate record and more than 3 hrs record
			//$tracks = $tracks->unique('finish_track');   
					  
			foreach($tracks as $key => $tr){ 
				if(!$tr['task_type']){
					$tr['task_type'] = $tr['relations']['task']['attributes']['task_type'];
				}
				$totaltime = $tr['relations']['timeLog'];	
				foreach($totaltime as $key => $total){		
					$totallog = $total['attributes']['total_time'];
					$trackid = $total['attributes']['track_id'];    
					if(($totallog >= 10800 || $totallog >= '10800') && ($trackid == $tr['id']) && ($tr['task_type'] <> $tasktype['attributes']['id'])){
						array_push($trueval,$tr['id']);
					}
				} 
			} 
        }
		$tracks = $tracks->unique('id');
        return view('time_track.time_tracks_all', compact('tracks', 'date', 'trueval'));
    }
	
	//SN 05/31/2017: added below function to get task which need to archive archive
	public function all_archive($dateStart = false, $dateFinish = false) {
		if (!$dateStart && !$dateFinish){
		  $dateFinish = date('Y-m-d');
		  $dateStart = date('Y-m-d', strtotime('-29 day', strtotime($dateFinish)));
		}
		$active['start'] = $dateStart;
		$active['end'] =$dateFinish;
		$dateFinish = date_modify(date_create($dateFinish), '+1 day');
		//SN 06/21/2017: added below to set date at time track page
        $date = date('d-m-Y');		
        if(Auth::user()['original']['employe'] == 'Lead'){
			
			@$leadId = User::where('id', '=', Auth::user()->id)->first()['attributes']['users_team_id'];
            $trackUser = TimeTrack::select(DB::raw('time_track.*,time_track.done as doneval, users.name'))
                        ->join('tasks','tasks.id','=','time_track.task_id')
                        ->join('users','users.id','=','tasks.assign_to')
                        ->where('time_track.done','>','1')
						->where('time_track.finish_track', '>=', $dateStart)
                        ->where('time_track.finish_track', '<=', $dateFinish)
                        ->whereIn('tasks.assign_to', function($query)  use ($leadId) {
									$query->select(DB::raw('id'))
											->from('users')
                                            ->where('users_team_id','=',$leadId);
                        });
			$trackProject = TimeTrack::select(DB::raw('time_track.*,time_track.done as doneval, users.name'))
                            ->join('tasks','tasks.id','=','time_track.task_id')
							->join('users','users.id','=','tasks.assign_to')
							->where('time_track.done','>','1')
							->where('time_track.finish_track', '>=', $dateStart)
                            ->where('time_track.finish_track', '<=', $dateFinish)
							->whereIn('tasks.project_id', function($projquery) use ($leadId) {
                                            $projquery->select(DB::raw('id'))
                                                      ->from('Project')
                                                      ->where('lead_id','=',$leadId);
                            });


			$tasks = $trackUser->union($trackProject)->orderByRaw('doneval=1 desc, doneval asc')
                    ->with('task', 'project')->get();	
		} else if(Auth::user()['original']['employe'] == 'Admin' || Auth::user()['original']['employe'] == 'Super Admin' || Auth::user()['original']['employe'] == 'Supervisor'){
			$tasks = TimeTrack::select(DB::raw('time_track.*,users.name'))
					  ->where('time_track.done', '>', '1')
					  ->join('tasks','tasks.id','=','time_track.task_id')
					  ->join('users','users.id','=','tasks.assign_to')
					  ->where('time_track.finish_track', '>=', $dateStart)
                      ->where('time_track.finish_track', '<=', $dateFinish)
					  ->with('task', 'project')
					  ->orderByRaw('time_track.done=1 desc, time_track.done asc')
                      ->get();
		}	
		
		return view('time_manage.taskarchive', compact('tasks', 'date','active'));
    }

	/** Mith 05/30/2017:
      * approve all checked task.
    */
    public function allTaskApproveTask( Request $request) {
          $data =  Input::all();
          foreach ($data['task_ids'] as $key) {
              $this->approveTask($key);
          }
    }

    /*
     * approve trask
     * */
    public function approveTask( $id )
    {
		//Mith 08/02/2017: Uncomment below code to start task approval email sending functionality.
		/*
		$tracks = TimeTrack::where('id', '=' , $id)->get()->first();
		$task = Task::where('id', '=', $tracks['attributes']['task_id'])->get()->first();
		$user = User::where('id', '=' ,$task['attributes']['assign_to'])->get()->first();
		$email = $user['attributes']['email'];
		$name  = $user['attributes']['name'];
		$task_detail = $task['attributes']['task_titly'];

		if($task['attributes']['task_description']){
			$description = $task['attributes']['task_description'];
		}else{
			$description = '';
		}
		if(Auth::User()->name){
			$lead = Auth::User()->name;
		}else{
			$lead = '';
		}
		//Mith 05/01/2017: updated below line of code to limit email subject.
		if (strlen($task_detail) > 78) {
			$cropStr = substr($task_detail, 0, 78) . '...';
			$subject = "Approved : ".$cropStr;
		} else {
			$subject = "Approved : ".$task_detail;
		}
		$status = "Approved";
		$comments = "";

		//SN 04/21/2017: updated below line of code
        //TimeTrack::where('id', '=', $id)
        //    ->update([ 'approve' => 1 ]);

		TimeTrack::where('id', '=', $id)
            ->update([ 'done' => 2 ]);

		if(Mail::to($email)->queue(new sendStatusMail($name,$status,$subject,$task_detail,$description,$lead,$comments))){
			echo "mail sent";
		} */
		TimeTrack::where('id', '=', $id)->update([ 'done' => 2 ]);
        return back();
    }

    /*
     * reject task
     * */
    public function rejectTrask( $id )
    {
		$tracks = TimeTrack::where('id', '=' , $id)->get()->first();
		$task = Task::where('id', '=', $tracks['attributes']['task_id'])->get()->first();
		$user = User::where('id', '=' ,$task['attributes']['assign_to'])->get()->first();
		$email = $user['attributes']['email'];
		$name  = $user['attributes']['name'];
		$task_detail = $task['attributes']['task_titly'];

		if($task['attributes']['task_description']){
			$description = $task['attributes']['task_description'];
		}else{
			$description = '';
		}
		//Mith 05/01/2017: updated below line of code to limit email subject.
		if (strlen($task_detail) > 78) {
			$cropStr = substr($task_detail, 0, 78) . '...';
			$subject = "Rejected : ".$cropStr;
		} else {
			$subject = "Rejected : ".$task_detail;
		}
		//$subject = "Rejected : ".$task_detail;
		$status = "Rejected";
		if(Auth::User()->name){
			$lead = Auth::User()->name;
		}else{
			$lead = '';
		}
		if(Input::all()){
			$data =  Input::all();
			if($data['comment-box']){
				if($tracks['attributes']['description']){
					$comment = $lead ." ".date('m/d/Y h:i A', time())." : ". $data['comment-box'];
					$comment = "\n".$comment."\n";
					$comment = $tracks['attributes']['description']. "". $comment;
				}else{
					$comment = $lead ." ".date('m/d/Y h:i A', time())." : ". $data['comment-box'];
				}
				$comments = $data['comment-box'];
			}
		}else{
			$comment = $tracks['attributes']['description'];
			$comments = "";
		}
		//SN 04/21/2017: updated below line of code
        //TimeTrack::where('id', '=', $id)->update([ 'approve' => 0 , 'description' => $comment]);

		TimeTrack::where('id', '=', $id)
            ->update([ 'done' => 3 ,
			'description' => $comment
		]);

		if(Mail::to($email)->send(new sendStatusMail($name,$status,$subject,$task_detail,$description,$lead,$comments))){
			echo "mail sent";
		}

        return back();
    }

    /*
     * finish track
     * */
    public function trackDone( $id )
    {

        TimeTrack::where('id', '=', $id)
            ->update(['done' => 1 ]);

        $trackId = TimeTrack::where('id', '=', $id)
            ->select('task_id')
            ->first()['attributes']['task_id'];

        Task::where('id', '=', $trackId)
            ->update([
                'date_finish' => date('Y-m-d H:i:s'),
                'done' => 1
            ]);

        return back();
    }

    /*
     * again return track to work
     * */
    public function trackReturnToWork( $id )
    {
        TimeTrack::where('id', '=', $id)
            ->update(['done' => 0 ]);

        $trackId = TimeTrack::where('id', '=', $id)
            ->select('task_id')
            ->first()['attributes']['task_id'];

        Task::where('id', '=', $trackId)
            ->update([
                'date_finish' => null,
                'done' => 0
            ]);

        return back();
    }

    /*
     * create time log
     * action works with ajax
     * */

    
    public function getTimeLogById($id,$trackDate){
		$trackDate = date_modify(date_create($trackDate), '+1 day');
        $timeLog = TimeLog::where('task_id', '=', $id)
                   ->where('start','<=',$trackDate)
                   ->with('task', 'project')->get();	
		return response()->json(['data' => (object)$timeLog]);
    }

    /*
     * create time log
     * */
    public function create_time_log( $id = false )
    {
		$todayDate = date('Y-m-d');
        $data =  Input::all();
        
	/*	if( isset($data['id'])) {
            $this->trackFinish($data['id']);
			return back();
        } */

		if( isset($data['id'])) {
            $runningTask = TimeTrack::select(DB::raw('time_log.*'))
                ->join('tasks','tasks.id','=','time_track.task_id')
                ->join('time_log','time_log.track_id','=','time_track.id')
                ->where('tasks.assign_to', '=', Auth::user()['original']['id'])
                ->where('time_track.track_date', '=',date('Y-m-d', strtotime($todayDate)))
                ->where('time_log.task_status','=',1)
                ->whereNull('time_log.finish')
                ->first()['attributes']['id'];
            if(strlen($runningTask) > 0 && $runningTask == $data['id']){
              $this->trackFinish($data['id']);
            } else{
              setcookie("logTrackActiveStart", "", time()-10, "/");
              setcookie("logTrackActiveTrackId", "", time()-10, "/");
              setcookie("logTrackActiveLogId", "", time()-10, "/");
            }
			return back();
        }

        if( Input::all()) {

            if(isset($_COOKIE['logTrackActiveLogId'])){
                $this->trackFinish($_COOKIE['logTrackActiveLogId']);
            }else {
              // Mith 11/28/2017: added below query to get current user tracking task time log info.
              $runningTask = TimeTrack::select(DB::raw('time_log.*'))
                    ->join('tasks','tasks.id','=','time_track.task_id')
                    ->join('time_log','time_log.track_id','=','time_track.id')
                    ->where('tasks.assign_to', '=', Auth::user()['original']['id'])
                    ->where('time_track.track_date', '=',date('Y-m-d', strtotime($todayDate)))
                    ->where('time_log.task_status','=',1)
                    ->whereNull('time_log.finish')
                    ->first()['attributes']['id'];
                    
              if(strlen($runningTask) > 0 ) {return back();}

            }

            $start =  Input::all();
            $start['start'] = date('Y-m-d H:i:s');
			$start['task_status'] = 1;
			
            TimeLog::create($start);

            $timeLog = Timelog::orderBy('id', 'desc')
                ->select(['id', 'start'])
                ->limit(1)
                ->get();



            setcookie('logTrackActiveStart', $start["start"], time() + (86400 * 30), "/");
            setcookie('logTrackActiveLogId', $timeLog[0]->id, time() + (86400 * 30), "/");
            setcookie('logTrackActiveTrackId', $start["track_id"], time() + (86400 * 30), "/");

            return response()->json(['data' => (object)$timeLog]);
			return back();
        }

        return false;
    }

    private function trackFinish($id)
    {
        $data['finish'] = date('Y-m-d H:i:s');
        $data['id'] = $id;
		$data['task_status'] = 0;

        ( new TimeLog() )->totalTime($data);

        setcookie("logTrackActiveStart", "", time()-10, "/");
        setcookie("logTrackActiveTrackId", "", time()-10, "/");
        setcookie("logTrackActiveLogId", "", time()-10, "/");
    }

    /*
     * delete time log
     * */
    public function deleteTraskLog( $id )
    {
        $traskId = 0;
        $traskId = TimeLog::where('id', '=', $id)
            ->select('track_id')
            ->first();

        TimeLog::where('id', '=', $id)
            ->delete();

        (new TimeLog())->totalTimeTrack( $traskId['attributes']['track_id'] );

        return back();
    }

    /* public function getTasks($project_id)
    {
        //SN 05/15/2017: updated below code
        if(Auth::user()->employe == 'Lead'){
          $leadid = Auth::user()->id;
          $result = Task::where('project_id', '=', $project_id)->where('assign_to', '=', $leadid)->get();
        }else{
          $result = Task::where('project_id', '=', $project_id)->get();
        }
        if ($result) {
            $result = response()->json(['data' => (object)$result]);
        } else {
            $result = response()->json(['data' => 'false']);
        }

        return $result;
    } */
	
	public function getTasks($project_id)
    {
        //SN 05/15/2017: updated below code
        if(Auth::user()->employe == 'Lead'){
          $leadid = Auth::user()->id;
		  //SN 10/05/2017: updated below only that task appearing which is not approved.
          //$result = Task::where('project_id', '=', $project_id)->where('assign_to', '=', $leadid)->get();
		  $allTracked = Task::select('tasks.*')->where('tasks.project_id', '=', $project_id)
						->where('assign_to', '=', $leadid)
						->where('time_track.done', '<>', 2)
						->join('time_track','time_track.task_id', '=', 'tasks.id');
						
		  $allNewTask = Task::where('project_id', '=', $project_id)->where('assign_to', '=', $leadid)->Where('tasks.done', '=', 0);
		  $result = $allTracked->union($allNewTask)->orderBy('id', 'asc')->with('track')->get();
        }else{
		  //SN 10/05/2017: updated below only that task appearing which is not approved.
          //$result = Task::where('project_id', '=', $project_id)->get();
		  $allTracked = Task::select('tasks.*')->where('tasks.project_id', '=', $project_id)
						->where('time_track.done', '<>', 2)
						->join('time_track','time_track.task_id', '=', 'tasks.id');
						
		  $allNewTask = Task::where('project_id', '=', $project_id)->Where('tasks.done', '=', 0);	
		  $result = $allTracked->union($allNewTask)->orderBy('id', 'asc')->with('track')->get();
        }
        if ($result) {
            $result = response()->json(['data' => (object)$result]);
        } else {
            $result = response()->json(['data' => 'false']);
        }

        return $result;
    }

    private function validation_track($request)
    {
        $this->validate($request, [
            'task_id' => 'required',
            'project_id' => 'required',
            'duration' => 'required',
            'date_start' => '',
            'date_finish' => '',
            'description' => 'max:1000',
            'additional_cost' => 'integer',
            'billable_time' => ''
        ], [
            'description.required' => 'field can not be blank'
        ]);
    }

    /*
     * get start time from log & now date by ajax
     * */
    public function getTimeStartLogById( $id )
    {
        $date['start'] = TimeLog::where('id', '=', $id)
						->whereNull('finish')
						->select('start')
						->first()['attributes']['start'];

        $date['now'] = date('Y-m-d H:i:s');
        return response()->json(['data' => $date]);
    }

    public function getTimeNow(){
        $data = date('Y-m-d H:i:s');
        return response()->json(['data' => $data]);
    }

    public function getTaskDescription($id){
        $description = task::where('id', '=', $id)->get();
        return response()->json(['data' => $description]);
    }
}
