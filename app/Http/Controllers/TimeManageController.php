<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Middleware\Authenticate;
use App\Client;
use App\Project;
use App\Task;
use App\User;
use App\TaskType;
use App\TaskStatus;
use App\TimeTrack;
use App\TimeLog;
use Validator;
use Mail;
use App\Mail\sendTaskMail;
use App\Mail\sendUserReport;
use DateTime;
use DateTimeZone;

class TimeManageController extends Controller
{
    /**
     * home page
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
         if (Auth::guest()) {
            return view('auth.login');
        } else {
            //return view('home');
			if (Auth::user()->employe == 'Super Admin' || Auth::user()->employe == 'Admin' || Auth::user()->employe == 'Supervisor' ) {
                return view('layouts.index_template');
            }else {
                return $this->dashboard();
            }
        }
    }
	
	public function dashboard (){
	 $date = date('Y-m-d');
	 $pageDate = date('l, F jS Y');
	 $todayTasks = array();
     if(Auth::user()['original']['employe'] == 'Lead'){
		   @$teamId = User::where('id', '=', Auth::user()->id)->first()['attributes']['users_team_id'];
		   $taskAssignTo = Task::select('tasks.*','users.name')
						   ->join('time_track','tasks.id','=','time_track.task_id', 'left outer')
						   ->join('users','users.id','=','tasks.assign_to')
						   ->where('tasks.done','=','0')
						   ->whereNull('time_track.task_id')
						   ->whereIn('assign_to', function($query)  use ($teamId) {
							   $query->select(DB::raw('id'))
									 ->from('users')
									 ->where('users_team_id','=',$teamId);
						   });
		   $taskAssignBy = Task::select('tasks.*','users.name')
						   ->join('time_track','tasks.id','=','time_track.task_id', 'left outer')
						   ->join('users','users.id','=','tasks.assign_to')
						   ->where('tasks.done','=','0')
						   ->whereNull('time_track.task_id')
						   ->whereIn('tasks.project_id', function($projquery) use ($teamId) {
							   $projquery->select(DB::raw('id'))
										 ->from('Project')
										 ->where('lead_id','=',$teamId);
						   });
		   $todayTasks = $taskAssignTo->union($taskAssignBy)->with(['Project', 'client'])->get();

		   $tracksUsers = TimeTrack::select('time_track.*', 'users.name')
					      ->join('tasks','tasks.id','=','time_track.task_id')
					      ->join('users','users.id','=','tasks.assign_to')
					      ->where('time_track.done', '=', '0')
					      ->whereIn('tasks.assign_to', function($query)  use ($teamId) {
										  $query->select(DB::raw('id'))
												->from('users')
												->where('users_team_id','=',$teamId);
					      });
		   $tracksProjs = TimeTrack::select('time_track.*', 'users.name')
						  ->join('tasks','tasks.id','=','time_track.task_id')
					      ->join('users','users.id','=','tasks.assign_to')
					      ->where('time_track.done', '=', '0')
					      ->whereIn('tasks.project_id', function($projquery) use ($teamId) {
										   $projquery->select(DB::raw('id'))
													 ->from('Project')
													 ->where('lead_id','=',$teamId);
						  });
		   $tracks = $tracksUsers->union($tracksProjs)->with('task', 'project')->get();
		   
		   $approvetask = TimeTrack::select(DB::raw('time_track.*, users.name,tasks.task_titly,Project.project_name'))
					     ->join('tasks','tasks.id','=','time_track.task_id')
					     ->join('users','users.id','=','tasks.assign_to')->join('Project','Project.id','=','time_track.project_id')
					     ->where('time_track.done', '=', 1)
					     ->whereIn('tasks.assign_to', function($query)  use ($teamId) {
										  $query->select(DB::raw('id'))
												->from('users')
												->where('users_team_id','=',$teamId);
					     })->get();
		
		   return view('time_manage.dashboard', compact('todayTasks', 'pageDate', 'tracks','approvetask'));
     } else {
		   
		  $todayTasks = Task::select('tasks.*','users.name')
					   ->join('time_track','tasks.id','=','time_track.task_id', 'left outer')
					   ->join('users','users.id','=','tasks.assign_to')
					   ->where('tasks.done','=','0')
					   ->whereNull('time_track.task_id')
					   ->where('assign_to', '=', Auth::user()['original']['id'])
					   ->with(['Project', 'client'])->get();

          $tracks  = TimeTrack::select('time_track.*', 'users.name')->with('task', 'project')
                     ->where('time_track.done', '=', '0')
                     ->where('tasks.assign_to','=',Auth::user()['original']['id'])
                     ->join('tasks','tasks.id','=','time_track.task_id')
                     ->join('users','users.id','=','tasks.assign_to')
                     ->get();
			
		   return view('time_manage.dashboard', compact('todayTasks', 'pageDate', 'tracks'));
     }
   }
   
   public function getApproveTask(){
        @$leadId = User::where('id', '=', Auth::user()->id)->first()['attributes']['users_team_id'];
		$approvetask = TimeTrack::select(DB::raw('time_track.*, users.name,tasks.task_titly,Project.project_name'))
					  ->join('tasks','tasks.id','=','time_track.task_id')
					  ->join('users','users.id','=','tasks.assign_to')->join('Project','Project.id','=','time_track.project_id')
					  ->where('time_track.done', '=', 1)
					  ->whereIn('tasks.assign_to', function($query)  use ($leadId) {
										  $query->select(DB::raw('id'))
												->from('users')
												->where('users_team_id','=',$leadId);
					})->get();
		$result = ['approvetask' => $approvetask];
        if ($result) {
            $result = response()->json(['data' => (object)$result]);
        } else {
            $result = response()->json(['data' => 'false']);
        }
        return $result;
   }

    /*
     * return all users
     * */
    public function all($team = false)
    {
        if ($team) {
            $users = User::where('team_name', '=', $team)
                ->orderBy('id', 'desc')
                ->get();
        } else {
			//Mith: 04/21/17: sort the user name in alphabetical order.
			$users = DB::table('users')
					->orderBy('name', 'asc')
					->orderBy('employe', 'asc')
					->leftJoin('teams', 'users.users_team_id', '=', 'teams.id')
					->select('users.id',
						'users.name',
						'users.email',
						'users.users_team_id',
						'users.hourly_rate',
						'users.created_at',
						'users.employe',
						'teams.teams_lead_id',
						'teams.team_name')
					->get();
				
			$lead = array();
			$leads = DB::table('users')->where('employe','=','lead')->get();
        }
        //return view('time_manage.users', compact('users'));
		return view('time_manage.users', compact('users', 'leads'));
    }

    /*
     * return all teams
     * */
    public function team_all()
    {	//Mith: 04/21/17: sort the team name in alphabetical order.
        $teams = DB::table('teams')
            ->orderBy('team_name', 'asc')
            ->leftjoin('users', 'teams.teams_lead_id', '=', 'users.id')
            ->select('teams.id',
                'users.name',
                'teams.teams_lead_id',
                'teams.team_name')
            ->get();

        return view('time_manage.teams', compact('teams'));
    }

    public function getProjects($client_id)
    {
		//SN 05/08/2017: updated below code to get project name in alphabetical order
        $result = DB::table('Project')->where('client_id', '=', $client_id)->orderBy('project_name','asc')->get();
        if ($result) {
            $result = response()->json(['data' => (object)$result]);
        } else {
            $result = response()->json(['data' => 'false']);
        }
        return $result;
        //return response()->json(['data' => (object)$result]);
    }

    /*
     * create new clients
     * */
    public function create_client(Request $request)
    {
        if (Input::all()) {
            $this->validation_client($request);

            $client = Input::all();

            if (!empty($client['website'])) {
                if (parse_url($client['website'], PHP_URL_SCHEME) == "http" || parse_url($client['website'], PHP_URL_SCHEME) == "https") {
                    $website = $client['website'];
                } else {
                    $website = 'http://' . $client['website'];
                }
            } else {
                $website = '';
            }

            Client::create([
                'company_name' => $client['company_name'],
                'company_address' => $client['company_address'],
                'website' => $website,
                'contact_person' => $client['contact_person'],
                'email' => $client['email'],
                'phone_number' => $client['phone_number']
            ]);

            return redirect('/client/all');
        }

        return view('time_manage.forms.client');
    }

    /*
     * update client
     * id - client id
     * */
    public function update_client(Request $request, $id)
    {
        if (Input::all() && Client::where('id', '=', $id)) {
            $this->validationClientEdit($request);

            $client = Input::all();

            $website ='';
            if (parse_url($client['website'], PHP_URL_SCHEME) == "http" || parse_url($client['website'], PHP_URL_SCHEME) == "https") {
                $website = $client['website'];
            } else if(!empty($client['website'])) {
                $website = 'http://' . $client['website'];
            }

            Client::where('id', '=', $id)->update([
                'company_name' => $client['company_name'],
                'company_address' => $client['company_address'],
                'website' => $website,
                'contact_person' => $client['contact_person'],
                'email' => $client['email'],
                'phone_number' => $client['phone_number']
            ]);
            return redirect('/client/all');
        }
        $client = DB::table('Clients')->where('id', '=', $id)->first();
        //$client = Client::where( 'id', '=', $id );


        return view('time_manage.forms.client', compact('client'));
    }

    /*
     * delete client
     * */
    public function delete_client($id)
    {
        Client::where('id', '=', $id)->delete();

        return redirect('/client/all');
    }

    /*
     * return all Client
     * */
    public function all_client()
    {	//Mith: 04/21/17: sort the company name in alphabetical order.
        $clients = DB::table('Clients')
            ->orderBy('company_name', 'asc')
            ->get();

        return view('time_manage.clients', compact('clients'));
    }

    /*
     * create project for company
     * */
    public function create_project(Request $request)
    {
        if (Input::all()) {
            $this->validation_project($request);

            $project = Input::all();

            if ($project['hourly_rate'] == '') {
                $project['hourly_rate'] = '0';
            }

            Project::create([
                'client_id' => $project['company_id'],
                'lead_id' => $project['lead_id'],
                'project_name' => $project['project_name'],
                'hourly_rate' => $project['hourly_rate'],
                'notes' => $project['notes']
            ]);

            return redirect('/project/all');
        }

		$client = Client::orderBy('company_name','asc')->get();
        $leads = User::where('employe', '=', 'Lead')->orderBy('name','asc')->get();

        return view('time_manage.forms.project', compact('client', 'leads'));
    }

    /*
     * update project
     * */
    public function update_project(Request $request, $id)
    {
        if (Input::all() && Project::where('id', '=', $id)) {
            $this->validation_project($request);

            $project = Input::all();

            Project::where('id', '=', $id)->update([
                'client_id' => $project['company_id'],
                'lead_id' => $project['lead_id'],
                'project_name' => $project['project_name'],
                'hourly_rate' => $project['hourly_rate'],
                'notes' => $project['notes']
            ]);

            return redirect('/project/all');
        }

        $project = Project::where('id', '=', $id)->get();
        $client = Client::orderBy('company_name','asc')->get();
		$project_client = Client::where('id', '=', $project[0]->client_id)->get();
        $lead = User::where('id', '=', $project[0]->lead_id)->get();
        $leads = User::where('employe', '=', 'Lead')->orderBy('name','asc')->get();

        return view('time_manage.forms.project', compact('project', 'client', 'lead', 'leads', 'project_client'));
    }

    /*
     * delete project
     * */
    public function delete_project($id)
    {
        Project::where('id', '=', $id)->delete();
        return redirect('/project/all');
    }

    /*
     * return all project .
     * */
    public function all_project()
    {	//Mith: 04/21/17: sort the project name in alphabetical order.
		$projects = array();
        if (Auth::user()['original']['employe'] == 'Supervisor' || Auth::user()['original']['employe'] == 'Admin' || Auth::user()['original']['employe'] == 'Super Admin') {
			$projects = DB::table('Project')
                ->orderBy('project_name', 'asc')
                ->leftJoin('users', 'Project.lead_id', '=', 'users.id')
                ->join('Clients', 'Project.client_id', '=', 'Clients.id')
                ->select('Project.project_name',
                    'Project.id',
                    'Project.hourly_rate',
                    'Project.notes',
                    'Project.created_at',
                    'users.name', 'Clients.company_name')
                ->get();
        } else {
			return redirect('/');
		}
		return view('time_manage.projects', compact('projects'));
    }


    /*
     * return all projects
     * with client id
     */
    public function client_projects(Request $request, $id)
    {
        $projects = Project::where('client_id', '=', $id)
            ->leftJoin('users', 'Project.lead_id', '=', 'users.id')
            ->join('Clients', 'Project.client_id', '=', 'Clients.id')
            ->select('Project.project_name',
                'Project.id',
                'Project.hourly_rate',
                'Project.notes',
                'Project.created_at',
                'users.name', 'Clients.company_name')
            ->get();

        $client = Client::where('id', '=', $id)->first();
        $projectsForClient = true;

        return view('time_manage.projects', compact('projects', 'client', 'projectsForClient'));
    }

    /*
     * create tack for project
     * */
    public function create_task(Request $request)
    {
		$session_token;
        $session_keyname;
        $session_keyvalue;
        if (Input::all()) {
            if (Auth::user()->employe == 'Developer') {
                $this->validation_task_developer($request);
            }else {
                $this->validation_task($request);
            }
            $task = Input::all();
			
			//MITH 11/17/2017: added below code to stop multiple submission of task creation form.
			$session_keyname = $task['_token']."-createtask";
            if (Session::has($session_keyname)){
              $session_keyvalue = Session::get($session_keyname);
              $diffssss = strtotime(date('Y-m-d H:i:s')) - strtotime($session_keyvalue);
              if($diffssss < 15){
                return redirect('/task/all');
              }
            }

            if (!isset($task['company_id'])) {
                $client_id = Project::where('id', '=', $task['project_id'])
                    ->select('client_id')->first();

                $task['company_id'] = Client::where('id', '=', $client_id->client_id)
                    ->select('id')->first()->id;
            }

            if (!isset($task['alloceted_hours']) || $task['alloceted_hours'] == '') {
                $task['alloceted_hours'] = 0;
            } else {
              $task['alloceted_hours'] = str_replace(':', '.', $task['alloceted_hours']);
            }
            if (!isset($task['assign_to']) || $task['assign_to'] == '') {
                $task['assign_to'] = 0;
            }
            if (!isset($task['task_description']) || $task['task_description'] == '') {
                $task['task_description'] = '';
            }
            if (!isset($task['billable'])) {
                $task['billable'] = false;
            }

            Task::create([
                'company_id' => $task['company_id'],
                'project_id' => $task['project_id'],
                'task_titly' => $task['task_titly'],
                'alloceted_hours' => $task['alloceted_hours'],
                'assign_to' => $task['assign_to'],
                'task_type' => $task['task_type'],
                'task_description' => $task['task_description'],
                'billable' => $task['billable'],
				'task_assign_by' => $task['task_assign_by']
            ]);
			
			//MITH 11/17/2017: added below code to stop multiple submission of task creation form.
			$session_token = date('Y-m-d H:i:s');
            Session::put($session_keyname, $session_token);
            Session::save();
			
			//SN 05/23/2017: added below code to send email when task create
			if($task['assign_to']){
				$this->sendtaskmail($task['assign_to'], $task['task_titly']);
			}

            return redirect('/task/all');
        }

        if (Auth::user()->employe == 'Developer') {
            if (Auth::user()->users_team_id == 0) {
                return redirect('/task/all/You aren\'t invited to the team/jgrowl-warning');
            }
            //Mith: 05/04/17: instead of team fetech data according to lead.
            $lead_id =  Auth::user()->users_team_id;
            $projects;
            if(Auth::user()->projects !== ''){
              $projectArr = explode(',', Auth::user()->projects);
              $queryIn = Project::whereIn('id',$projectArr);
              $queryLead = Project::where('lead_id', '=', $lead_id)->orderBy('project_name','asc');
              $projects = $queryIn->union($queryLead)->with('client')->orderBy('project_name','asc')->get();
            }else {
              $projects = Project::where('lead_id', '=', $lead_id)->orderBy('project_name','asc')
                  ->with('client')
                  ->get();
            }
            $tasktype = TaskType::orderBy('task_type','asc')->get(); //Mith: 05/02/17: get all task type data and pass it to view to populate task type data from database.
            $user = User::where('id', '=', Auth::user()->id)->first();
            return view('time_manage.forms.taskForm', compact('projects','tasktype','user'));
        }

        $client = Client::orderBy('company_name','asc')->get();		
		$project = Project::orderBy('project_name','asc')->get();   
        $tasktype = TaskType::orderBy('task_type','asc')->get(); //Mith: 03/29/17: get all task type data and pass it to view to populate task type data from database.
        return view('time_manage.forms.taskForm', compact('client', 'project','tasktype'));
    }
	
	/* *
     * Send email to task assignee, No mail will be send if assined to login user.
     * */
	public function sendtaskmail($id, $task_title){
		$id = $id;
		$user = User::where('id', '=', $id)->get()->first();  
		$name = $user['name'];
		$email = $user['email'];
		$userstatus = $user['employe'];   
		$task_detail = $task_title;
		$lead = Auth::User()->name;
		$leademail = Auth::user()->email;
		$status = '';
		$subject = 'Task Assigned : '.$task_detail; 
		
		if((Auth::User()->employe == 'Lead') || (Auth::User()->employe == 'Admin') || (Auth::User()->employe == 'Super Admin') || (Auth::User()->employe == 'Supervisor') || (Auth::User()->employe == 'HR Manager') || (Auth::User()->employe == 'QA Engineer')){
			if(Auth::User()->id <> $id){
				Mail::to($email)->send(new sendTaskMail($name, $subject, $task_detail, $lead, $leademail));
			}
		}
	}
	//SN 09/20/2017: added below function to add leave task
	public function setleavetask(Request $request){     
		if(Input::all()){
			$task = Input::all();
			//$date = date('Y-m-d');
			//$finishdate = date('Y-m-d H:i:s');
			
			//$date = $task['date-leave'];
			$date = date('Y-m-d H:i:s');
			$finishdate = date('Y-m-d H:i:s', strtotime($date));   
			$finishtrack = date('Y-m-d H:i:s', strtotime($task['date-leave']));
			$hours = ''; 
			$totaltrack = ''; 
			$totallog = '';
			$sepchr = '';
			$t = '';				
			$from = '';$end = '';
			$start = '';
			$finish = '';
			
			if($task['leave_type'] == 'full-day'){
				$hours = 08.00;
				
				$startedAt = $task['date-leave'].' 09:30:00';
				$start = $this->convertTimezone($startedAt);
				$finishAt = $task['date-leave'].' 17:30:00';
				$finish = $this->convertTimezone($finishAt);
				
				$start = date("Y-m-d H:i:s", strtotime($start));
				$finish = date("Y-m-d H:i:s", strtotime($finish));    
			}elseif($task['leave_type'] == 'half-day'){
				$hours = 04.00;
				
				if($task['half_day_type'] == 'Morning'){
					$startedAt = $task['date-leave'].' 09:30:00';
					$start = $this->convertTimezone($startedAt);
					$finishAt = $task['date-leave'].' 13:30:00';
					$finish = $this->convertTimezone($finishAt);
					
					$start = date("Y-m-d H:i:s", strtotime($start));
					$finish = date("Y-m-d H:i:s", strtotime($finish));
				}else if($task['half_day_type'] == 'Afternoon'){
					$startedAt = $task['date-leave'].' 14:30:00';
					$start = $this->convertTimezone($startedAt);
					$finishAt = $task['date-leave'].' 18:30:00';
					$finish = $this->convertTimezone($finishAt);
					
					$start = date("Y-m-d H:i:s", strtotime($start));
					$finish = date("Y-m-d H:i:s", strtotime($finish));
				}										
			}elseif($task['leave_type'] == 'spec-hrs'){
				//$hours = $task['end_time'] - $task['from_time'];
				$dateDiff = intval((strtotime($task['end_time'])- strtotime($task['from_time']))/60);
				$h = intval($dateDiff/60);
				$m = $dateDiff%60;
				$hours = $h.".".$m;
				
				/*$hr = explode('.', $hours);  
				if(array_key_exists(1, $hr) == true){
					if($hr[1]){
						$t = $hr[0].' hour '.$hr[1].' minutes';		
					}else{
						$t = $hr[0].' hour';
					}
				}else{
					$t = $hr[0].' hour';
				}	*/																
				$hr = explode('.', $task['from_time']);  
				if(array_key_exists(1, $hr) == true){
					if($hr[1]){
						$t = $hr[0].':'.$hr[1].':00';		
					}else{
						$t = $hr[0].':00:00';
					}
				}else{
					$t = $hr[0].':00:00';
				}
				$hrs = explode('.', $task['end_time']);  
				if(array_key_exists(1, $hrs) == true){
					if($hr[1]){
						$end = $hrs[0].':'.$hrs[1].':00';		
					}else{
						$end = $hrs[0].':00:00';
					}
				}else{
					$end = $hrs[0].':00:00';
				}

				$startedAt = $task['date-leave'].' '.$t;
				$start = $this->convertTimezone($startedAt);   
				$finishAt = $task['date-leave'].' '.$end;
				$finish = $this->convertTimezone($finishAt);			
				
				$start = date('Y-m-d H:i:s', strtotime($start));
				$finish = date('Y-m-d H:i:s',strtotime($finish));		
			}	
			//dd($finish, $task);
			$hr = explode('.', $hours);     
			if(array_key_exists(1, $hr) == true){
				if(strlen($hr[1]) == 1){
					$hr[1] = $hr[1].'0';
				}
				$totaltrack = (int)$hr[0] * 60 + (int)$hr[1];
				$totallog = (int)$hr[0] * 60 * 60 + (int)$hr[1] * 60; 
			}else{
				$totaltrack = (int)$hr[0] * 60;
				$totallog = (int)$hr[0] * 60 * 60;
			}		
			
			$checkleave = Task::select('tasks.*')->join('time_track','time_track.task_id','=','tasks.id')->where('task_type', '=', $task['task_type'])
						->where('assign_to', '=', $task['assign_to'])->where('time_track.track_date', '=', $task['date-leave'])->with('track')->get()->first();
			
			if($checkleave){
				$task['date-leave'] = date('m-d-Y', strtotime($task['date-leave']));
				\Session::flash('flash_message','Leave is already added for '.$task['username'].' on '.$task['date-leave'].' ');
				return redirect('/tracking');
			}
			
			$createtask = Task::create([
						'company_id' => $task['client_id'],
						'project_id' => $task['project_id'],
						'task_titly' => $task['task_titly'],
						'alloceted_hours' => $hours,
						'assign_to' => $task['assign_to'],
						'done' => 1,
						'task_type' => $task['task_type'],
						'task_description' => $task['task_titly'],
						'billable' => 1,
						'task_assign_by' => Auth::User()->id,
						'trackdays' => 1
					]);
			$taskid = $createtask['id'];
			   
			$createtrack = TimeTrack::create([
						'done' => 1,
						'project_id' => $task['project_id'],
						'task_id' => $taskid,
						'duration' => $totaltrack,
						'billable_time' => 1,
						'total_time' => $totallog,
						'track_date' => $task['date-leave'],
						'finish_track' => $finish
			]);
			$trackid = $createtrack['id'];
			/*if(array_key_exists(1, $hr) == true){
				if($hr[1]){
					$t = $hr[0].' hour '.$hr[1].' minutes';
				}
			}else{
				$t = $hr[0]." hour";
			}	
			$finish = date('Y-m-d H:i:s',strtotime($t,strtotime($finishdate))); */
			$timelog = TimeLog::create([
					'project_id' => $task['project_id'],
					'task_id' => $taskid,
					'track_id' => $trackid,
					'start' => $start,
					'finish' => $finish,
					'total_time' => $totallog
			]);
			$logid = $timelog['id'];
			Task::where('id', '=', $taskid)->update(['done' => 1,'trackdays' => 1, 'date_finish' => $finish]);
			TimeTrack::where('id', '=', $trackid)->update(['finish_track' => $finish]);
			TimeLog::where('id', '=', $logid)->update(['total_time' => $totallog ]);
			return redirect('/tracking/');
		}
	}
	
	public function convertTimezone($time){
		$startFrom = new DateTime($time, new DateTimeZone('Asia/Kolkata'));
		$startFrom->setTimezone(new DateTimeZone(date_default_timezone_get()));
		$start = $startFrom->format('Y-m-d H:i:s');
		return $start;
	}

    /*
     *
     * update task
     * */
    public function update_task(Request $request, $id)
    {
		$projects = array();
		$task = array();
		$user = array();
		$tasktype = array();
		$project = array();
		
        if (Input::all() && Task::where('id', '=', $id)) {
            if (Auth::user()->employe == 'Developer') {
                $this->validation_task_developer($request);
            }else {
                $this->validation_task($request);
            }
            $task = Input::all();
            if (!isset($task['company_id'])) {
                $client_id = Project::where('id', '=', $task['project_id'])
                    ->select('client_id')->first();

                $task['company_id'] = Client::where('id', '=', $client_id->client_id)
                    ->select('id')->first()->id;
            }
            if (!isset($task['alloceted_hours']) || $task['alloceted_hours'] == '') {
                $task['alloceted_hours'] = 0;
            } else {
              $task['alloceted_hours'] = str_replace(':', '.', $task['alloceted_hours']);
            }
            if (!isset($task['assign_to']) || $task['assign_to'] == '') {
                $task['assign_to'] = 0;
            }
            if (!isset($task['task_description']) || $task['task_description'] == '') {
                $task['task_description'] = '';
            }
            if (!isset($task['billable'])) {
                $task['billable'] = false;
            }

            Task::where('id', '=', $id)->update([
                'company_id' => $task['company_id'],
                'project_id' => $task['project_id'],
                'task_titly' => $task['task_titly'],
                'assign_to' => $task['assign_to'],
                'task_type' => $task['task_type'],
                'task_description' => $task['task_description'],
                'alloceted_hours' => $task['alloceted_hours'],
                'billable' => $task['billable']
            ]);

            return redirect('/task/all');
        }

        if (Auth::user()->employe == 'Developer') {
            if (Auth::user()->users_team_id == 0) {
                return redirect('/task/all/You aren\'t invited to the team/jgrowl-warning');
            }

            $lead_id =  Auth::user()->users_team_id;
            if(Auth::user()->projects !== ''){
              $projectArr = explode(',', Auth::user()->projects);
              $queryIn = Project::whereIn('id',$projectArr);
              $queryLead = Project::where('lead_id', '=', $lead_id)->orderBy('project_name','asc');
              $projects = $queryIn->union($queryLead)->with('client')->get();
            }else {
              $projects = Project::where('lead_id', '=', $lead_id)->orderBy('project_name','asc')
                  ->with('client')
                  ->get();
            }
            $tasktype = TaskType::orderBy('task_type','asc')->get();
            $task = Task::where([['assign_to', '=', Auth::user()->id], ['id', '=', $id]])
                ->with('project')->get();
            $user = User::where('id', '=', $task[0]->assign_to)->first();
        }

        $task = Task::where('id', '=', $id)->get();
        $user = User::where('id', '=', $task[0]->assign_to)->first();
        
		$client = Client::orderBy('company_name','asc')->get();   
		$tasktype = TaskType::orderBy('task_type','asc')->get(); //Mith: 03/29/17: get all task type data and pass it to view to populate task type data from database.
		//SN 06/07/2017: updated below code to get projects associated to clients
		if((Auth::user()->employe == 'Lead') || (Auth::user()->employe == 'Admin') || (Auth::user()->employe == 'QA Engineer') || (Auth::User()->employe == 'Supervisor') || (Auth::User()->employe == 'HR Manager')){
			if($task[0]->company_id){
				$project = Project::where('client_id', '=', $task[0]->company_id)->orderBy('project_name','asc')->get();
			}else{
				$project = Project::orderBy('project_name','asc')->get();
			}
		}else{
			$project = Project::orderBy('project_name','asc')->get();
		}
        return view('time_manage.forms.taskForm', compact('projects', 'task', 'client', 'project', 'user','tasktype'));
    }
	
	/*
     * return all task types
     * */
    public function all_task_types($msg = '', $theme = '')
    {
        if (Auth::user()['original']['employe'] == 'Super Admin' || Auth::user()['original']['employe'] == 'Supervisor' || Auth::user()['original']['employe'] == 'Admin' || Auth::user()['original']['employe'] == 'Lead') {
            $tasks = TaskType::orderBy('id')->get();
            $i = 0;
			$tasksRes = array();
            foreach ($tasks as $task) {
                $tasksRes[$i]['id'] = $task->id;
                $tasksRes[$i]['title'] = $task->task_type;
                $tasksRes[$i]['description'] = $task->description;
                $tasksRes[$i]['created_at'] = $task->created_at;
                $i++;
            }
        }
        return view('time_manage.task_type', compact('tasksRes', 'msg', 'theme'));
    }

    /*
     * delete task
     * */
    public function delete_task($id)
    {
        Task::where('id', '=', $id)->delete();

        return redirect('/task/all');
    }

    /*
     * create task type for task
     * */
    public function create_task_type(Request $request)
    {
        if (Input::all()) {

            $this->validation_task_type($request);
            $taskType = Input::all();

            if (!isset($taskType['description']) || $taskType['description'] == '') {
                $taskType['description'] = '';
            }

            TaskType::create([
                'task_type' => $taskType['task_type'],
                'description' => $taskType['description']
            ]);

            return redirect('/task-type/all');
        }
        return view('time_manage.forms.taskTypeForm');
    }

    /*
     * update task type
     * */
    public function update_task_type(Request $request, $id)
    {
        if (Input::all() && Project::where('id', '=', $id)) {
            $this->validation_task_type($request);
            $taskType = Input::all();

            TaskType::where('id', '=', $id)->update([
                'task_type' => $taskType['task_type'],
                'description' => $taskType['description']
            ]);
            return redirect('/task-type/all');
        }

        $taskType = TaskType::where('id', '=', $id)->get();
        return view('time_manage.forms.taskTypeForm', compact('taskType'));
    }

    /*
     * delete task type
     * */
    public function delete_task_type($id)
    {
        TaskType::where('id', '=', $id)->delete();

        return redirect('/task-type/all');
    }

    /*
     * return all tasks
     * */
    public function all_tasks($dateStart = false, $dateFinish = false) {
	    if(!$dateStart && !$dateFinish){
			$dateFinish = date('Y-m-d');
			$dateStart = date('Y-m-d', strtotime('-29 day', strtotime($dateFinish)));
	    }
		$active['start'] = $dateStart;
        $active['end'] =$dateFinish;
		$dateFinish = date_modify(date_create($dateFinish), '+1 day');
        $tasks = array();
		if (Auth::user()['original']['employe'] == 'Developer' || Auth::user()['original']['employe'] == 'QA Engineer') {
            $tasks = Task::where('assign_to', '=', Auth::user()['original']['id'])
					->where('created_at', '>=', $dateStart)
                    ->where('created_at', '<=', $dateFinish)
					->orderBy('id', 'desc')
					->with(['Project', 'client'])->get();
        } else if(Auth::user()['original']['employe'] == 'Lead'){
            //$userId = Auth::user()['original']['id'];
            //@$teamId = DB::table('teams')->where('teams_lead_id', '=', $userId)->first()->id;
            @$leadId = Auth::user()['original']['users_team_id'];
            $taskAssignTo = Task::where('created_at', '>=', $dateStart)
						   ->where('created_at', '<=', $dateFinish)
						   ->whereIn('assign_to', function($query)  use ($leadId){
                                $query->select(DB::raw('id'))
                                      ->from('users')
                                      ->where('users_team_id','=',$leadId);
                            });
            $taskAssignBy = Task::where('created_at', '>=', $dateStart)
						  ->where('created_at', '<=', $dateFinish)
						  ->where('task_assign_by','=',$leadId);

            $tasks = $taskAssignTo->union($taskAssignBy)->with(['Project', 'client'])->orderBy('id', 'desc')->get();
        } else if (Auth::user()['original']['employe'] == 'Supervisor' || Auth::user()['original']['employe'] == 'Admin' || Auth::user()['original']['employe'] == 'Super Admin') {

            $tasks = Task::orderBy('id', 'desc')
				   ->where('created_at', '>=', $dateStart)
                   ->where('created_at', '<=', $dateFinish)
				   ->with(['Project', 'client'])->get();
        }

        $i = 0;
		date_default_timezone_set("Asia/Kolkata");
        foreach ($tasks as $task) {

            $user = User::where('id', '=', $task->assign_to)->first();
            if (isset($user)) {
                $user_name = $user->name;
            } else {
                $user_name = '';
            }
			//SN 05/11/2017: added below code to get task-type
			$tasktype = TaskType::where('id', '=', $task->task_type)->get()->first();
			if(isset($tasktype)){
				$type = $tasktype->task_type;
			}else{
				$type = $task->task_type;
			}

            $tasksRes[$i]['user_name'] = $user_name;
            $tasksRes[$i]['id'] = $task->id;
            $tasksRes[$i]['title'] = $task->task_titly;
            $tasksRes[$i]['type'] = $type;
            $tasksRes[$i]['assign_to'] = $task->assign_to;
            $tasksRes[$i]['alloceted_hours'] = $task->alloceted_hours;
            $tasksRes[$i]['task_description'] = $task->task_description;
            $tasksRes[$i]['billable'] = $task->billable;
			$tasksRes[$i]['task_assign_by'] = $task->task_assign_by;
            $tasksRes[$i]['created_at'] = date("Y-m-d H:i:s", strtotime($task->created_at." UTC"));
            $tasksRes[$i]['company'] = $task->client['company_name'];
            $tasksRes[$i]['project_name'] = $task->project['project_name'];
            $i++;
        }
		date_default_timezone_set("UTC");
        return view('time_manage.tasks', compact('tasksRes', 'active'));
    }

    /*
     * return all tasks belows project
     * */
    public function get_project_tasks($project_id)
    {
        $tasks = Task::where('project_id', '=', $project_id)
            ->with(['Project', 'client'])
            ->get();
        $i = 0;

        foreach ($tasks as $task) {

            $user = User::where('id', '=', $task->assign_to)->first();
            if (isset($user)) {
                $user_name = $user->name;
            } else {
                $user_name = '';
            }

            $tasksRes[$i]['user_name'] = $user_name;
            $tasksRes[$i]['id'] = $task->id;
            $tasksRes[$i]['title'] = $task->task_titly;
            $tasksRes[$i]['type'] = $task->task_type;
            $tasksRes[$i]['assign_to'] = $task->assign_to;
            $tasksRes[$i]['alloceted_hours'] = $task->alloceted_hours;
            $tasksRes[$i]['task_description'] = $task->task_description;
            $tasksRes[$i]['billable'] = $task->billable;
            $tasksRes[$i]['created_at'] = $task->created_at;
            $tasksRes[$i]['company'] = $task->client['company_name'];
            $tasksRes[$i]['project_name'] = $task->project['project_name'];
            $i++;
        }

        $project = DB::table('Project')
            ->where('Project.id', '=', $project_id)
            ->leftJoin('users', 'Project.lead_id', '=', 'users.id')
            ->join('Clients', 'Project.client_id', '=', 'Clients.id')
            ->select('Project.project_name',
                'Project.id',
                'Project.hourly_rate',
                'Project.notes',
                'Project.created_at',
                'users.name', 'Clients.company_name')
            ->first();

        $tasksForProject = true;

        return view('time_manage.tasks', compact('tasksRes', 'tasksForProject', 'project'));
    }

    /*
     * return all tasks belows client
     * */
    public function get_client_tasks($client_id)
    {
        $tasks = Task::where('company_id', '=', $client_id)
            ->with(['client', 'user'])->get();

        return view('', compact('tasks'));
    }

    /*
     * finish task
     * */
    public function taskDone($id)
    {
        Task::where('id', '=', $id)
            ->update(['done' => 1]);

        return back();
    }

    /*
     * again return task to work
     * */
    public function taskReturnToWork($id)
    {
        Task::where('id', '=', $id)
            ->update(['done' => 0]);

        return back();
    }

	/**
	get task status
	**/
	public function all_task_status(){
		$status = TaskStatus::get();
		$taskstatus = array();
		$i=0;

		foreach($status as $sts){
			$taskstatus[$i]['id'] = $sts->id;
			$taskstatus[$i]['name'] = $sts->name;
			$taskstatus[$i]['description'] = $sts->description;
			$taskstatus[$i]['status_order'] = $sts->status_order;
			$i++;
		}

		return view('time_manage.status', compact('taskstatus'));
	}

	public function create_task_status(Request $request)
    {
        if (Input::all()) {
            $this->validation_task_status($request);
            $taskStatus = Input::all();
            if (!isset($taskStatus['description']) || $taskStatus['description'] == '') {
                $taskStatus['description'] = '';
            }

            TaskStatus::create([
                'name' => $taskStatus['task_status'],
                'description' => $taskStatus['description'],
				'status_order' => $taskStatus['status_order']
            ]);

            return redirect('/task-status/all/');
        }
        return view('time_manage.forms.taskStatusForm');
    }

	public function update_task_status(Request $request, $id = false){
		if (Input::all()) {
            //$this->validation_task_status($request);
			$this->validationtaskstatus($request,$id);
            $taskStatus = Input::all();

            TaskStatus::where('id', '=', $id)->update([
                'name' => $taskStatus['task_status'],
                'description' => $taskStatus['description'],
				'status_order' => $taskStatus['status_order']
            ]);
            return redirect('/task-status/all');
        }
		$status = TaskStatus::where('id', '=', $id)->get();
		$taskstatus = array();
		$i=0;

		foreach($status as $sts){
			$taskstatus[$i]['id'] = $sts->id;
			$taskstatus[$i]['name'] = $sts->name;
			$taskstatus[$i]['description'] = trim($sts->description);
			$taskstatus[$i]['status_order'] = $sts->status_order;
			$i++;
		}

        return view('time_manage.forms.taskStatusForm', compact('taskstatus'));
	}

	public function delete_task_status($id)
    {
        TaskStatus::where('id', '=', $id)->delete();

        return redirect('/task-status/all');
    }

	private function validation_task_status($request)
    {
        $this->validate($request, [
            'task_status' => 'String|required|min:2|max:50',
            'description' => 'regex:/[a-zA-Z0-9]+/|max:1000',
			'status_order' => 'required|unique:task_status'
        ]);
    }

	private function validationtaskstatus($request,$statusID)
    {
        $this->validate($request, [
            'task_status' => 'String|required|min:2|max:50',
            'description' => 'regex:/[a-zA-Z0-9]+/|max:1000',
			'status_order' => 'required|unique:task_status,status_order,'.$statusID
        ]);
    }

    /*
     * get team on project id
     *
     * */
    public function get_team($project_id)
    {

        $result = Project::where('id', '=', $project_id)
            ->get()[0]->lead_id;
        $collection = null;
            $team = null;
        if($result) {
            /** @var Collection $collection */
            $collection = User::where('id', '=', $result)->get();

        }

        if(($lead = $collection->get(0)) !== null) {
            $team = User::where('users_team_id', '=', $lead->users_team_id)->get();

        }

        $qa = User::where('employe', '=', 'QA Engineer')->get();
        if($team) {

            $other = User::where([
                ['id', '<>', $result],
                ['users_team_id', '<>', $collection[0]->users_team_id],
                ['employe', '<>', 'QA Engineer'],
            ])->get();
        } else {
            $other = User::where([
                ['id', '<>', $result],
                ['employe', '<>', 'QA Engineer'],
            ])->get();
        }



        $result = ['lead' => $collection, 'team' => $team, 'qa' => $qa, 'other' => $other];

        if ($result) {
            $result = response()->json(['data' => (object)$result]);
        } else {
			$result = response()->json(['data' => 'false']);
		}

        return $result;
    }
	/*
     * get All user list. 
     * */
    public function get_users()
    {
        //$users = User::get();
		//SN 05/09/2017: updated below code to get task assignee in alphabetical order
		$users = User::orderBy('name','asc')->get();
        $result = ['users' => $users];

        if ($result) {
            $result = response()->json(['data' => (object)$result]);
        } else {
			$result = response()->json(['data' => 'false']);
		}

        return $result;
    } 
	
    /*
     * create new team
     * */
    public function create_team(Request $request)
    {
        if (Input::all()) {
            $this->validation_team($request);
            $team = Input::all();

            if (!isset($team['teams_lead_id'])) {
                $team['teams_lead_id'] = 0;
            }

            DB::table('teams')->insert([
                'team_name' => $team['team_name'],
                'teams_lead_id' => $team['teams_lead_id']
            ]);

			// Mith: 08/14/2017 uncomment to update teams_lead_id in user table.
           /* $teamId = DB::table('teams')->where('teams_lead_id', '=', $team['teams_lead_id'])->first()->id;
            DB::table('users')->where('id', '=', $team['teams_lead_id'])->update(['users_team_id' => $teamId]); */

            return redirect('/team/all');
        }
        $leads = User::where('employe', '=', 'Lead')->orderBy('name','asc')->get();

        return view('time_manage.forms.createTeamsForm', compact('leads'));
    }

	/*
     * update team from table teams
     * */
    public function update_team(Request $request, $id)
    {
        if (Input::all()) {
            $this->validation_update_team($request);
            $team = Input::all();

            if (!isset($team['teams_lead_id'])) {
                $team['teams_lead_id'] = 0;
            }

            DB::table('teams')->where('id', '=', $id)->update([
              'team_name' => $team['team_name'],
              'teams_lead_id' => $team['teams_lead_id']
            ]);
            return redirect('/team/all');
        }

        $teams = DB::table('teams')->where('id', '=', $id)->first();
        $leads = User::where('employe', '=', 'Lead')->orderBy('name','asc')->get();

        return view('time_manage.forms.createTeamsForm', compact('teams','leads'));
    }
	
    /*
     * delete team from table teams
     * and change team_name field in table users
     * where this team used
     * */
    public function delete_team($id)
    {
        DB::table('users')
            ->where('users_team_id', '=', $id)
            ->update(['users_team_id' => 0]);

        DB::table('teams')->where('id', '=', $id)->delete();

        return redirect('/team/all');
    }

    /*
     * validation for clients action
     * */
    private function validation_client($request)
    {
        $this->validate($request, [
            'company_name' => 'required|min:4|max:100',
            'company_address' => 'min:4|max:100',
            'website' => 'string',
            'contact_person' => 'required|min:4|max:100',
            'email' => 'unique:Clients|email',
            'phone_number' => 'regex:/[0-9-]+/|max:30'
        ]);
    }

    private function validationClientEdit($request)
    {
        $this->validate($request, [
            'company_name' => 'required|min:4|max:100',
            'company_address' => 'min:4|max:100',
            'website' => 'string',
            'contact_person' => 'required|min:4|max:100',
            'email' => 'email',
            'phone_number' => 'regex:/[0-9-]+/|max:30'
        ]);
    }

    /*
     * validation for project
     * */
    private function validation_project($request)
    {
        $this->validate($request, [
            'company_id' => 'required|integer',
            'lead_id' => 'integer',
            'project_name' => 'required|min:2|max:100',
            'hourly_rate' => 'numeric',
            'notes' => 'regex:/[a-zA-Z0-9]+/|max:1000'
        ], [
            'company_id.required' => 'The company field is required'
        ]);
    }

    /*
     * validation for task
     * */
    private function validation_task($request)
    {
        $this->validate($request, [
            'company_id' => 'required|integer',
            'project_id' => 'required|integer',
            'task_type' => 'required|min:1|max:50',
            'task_description' => 'regex:/[a-zA-Z0-9]+/|max:1000',
            'task_titly' => 'required|min:2|max:200',
            //'alloceted_hours' => ['numeric', 'regex:/^(1[0-2]|0?[1-9]):([0-5]?[0-9])$/'],
            'alloceted_hours' =>['regex:/^([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/'],
            'assign_to' => 'required|min:2|max:30',
            'billable' => 'boolean'
        ], [
            'task_titly.max' => 'The task title may not greater than 200 characters.'
        ]);
    }

    /*
     * validation for task for developer
     * */
    private function validation_task_developer($request)
    {
        $this->validate($request, [
            'project_id' => 'required|integer',
            'task_type' => 'required|min:1|max:50',
            'task_description' => 'regex:/[a-zA-Z0-9]+/|max:1000',
            'task_titly' => 'required|min:2|max:200',
            //'alloceted_hours' => ['numeric', 'regex:/^(1[0-2]|0?[1-9]):([0-5]?[0-9])$/'],
            'alloceted_hours' =>['regex:/^([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/'],
            'assign_to' => 'required|min:2|max:30',
            'billable' => 'boolean'
        ], [
            'task_titly.max' => 'The task title may not greater than 200 characters.'
        ]);
    }

    /*
     * validation for task type
     * */
    private function validation_task_type($request)
    {
        $this->validate($request, [
            'task_type' => 'required|min:2|max:50',
            'description' => 'regex:/[a-zA-Z0-9]+/|max:1000'
        ]);
    }

    /*
     * validation for create team
     * */
    private function validation_team($request)
    {
        $this->validate($request, [
            'team_name' => 'required|unique:teams|min:2|max:30'
        ]);
    }

	/*
     * validation for update team
     * */
    private function validation_update_team($request)
    {
        $this->validate($request, [
            'team_name' => 'required:teams|min:2|max:30'
        ]);
    }
	
    /*
     * logout
     * */
    public function logout()
    {
		Auth::logout();
		Session::flush();
        return redirect('/');
    }

}
