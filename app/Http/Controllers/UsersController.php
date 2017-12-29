<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use Illuminate\Http\Request;
use Mail;
use App\Mail\mailCreateUser;

class UsersController extends Controller
{
    private $user;
	
	private $no_team = [
        'Supervisor',
        'Admin',
        'HR Manager',
        'QA Engineer',
        'Lead',
        'Super Admin'
    ];

    public function create(Request $request)
    {
       if(Input::all()) {
            $this->validation_create($request);
            $user = Input::all();

           if( in_array($user['employe'], $this->no_team ) ) {
              $user['projects'] = 0;
              $user['lead_id'] = 0;
           }
           if ( !isset($user['users_team_id']) || $user['users_team_id'] == '' )  {
               $user['users_team_id'] = 0;
           }
           if ( !isset($user['hourlyRate']) || $user['hourlyRate'] == '') {
               $user['hourlyRate'] = 0;
           }
           if ( isset($user['lead_id']) && $user['lead_id'] !== 0) {
               $user['users_team_id'] = ($user['lead_id'] == '') ? 0 : $user['lead_id'];
           }
           if ( !isset($user['projects']) || $user['projects'] == '' )  {
               $user['projects'] = 0;
           }
           if(isset($user['projects']) && $user['projects'] !== 0){
              $user['projects'] = implode(",",$user['projects']);
           }

            $password = $this->password_generate();
            $createUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($password),
                'employe' => $user['employe'],
                'users_team_id' => $user['users_team_id'],
                'hourly_rate' => $user['hourlyRate'],
                'projects' => $user['projects']
            ]);
			// To update lead id for lead
            if($user['employe'] == 'Lead'){
              $insertedId = $createUser->id;
              DB::table('users')->where('id', '=', $insertedId)->update(['users_team_id' => $insertedId]);
            }
            Mail::to($user['email'])->send(new mailCreateUser($user['name'], $password, $user['email']));

            return redirect('/user/all');
        }

        $teams = DB::table('teams')->get();
        //SN 05/08/2017: updated below code for get options in alphabetical order
        $projects = DB::table('Project')->orderBy('project_name','asc')->get();
        $leads = DB::table('users')->where('employe','=','lead')->get();
        return view('auth.registration', compact('teams','projects','leads'));
    }
    /*
     * update user
     * $id - user id
     * */
    public function update(Request $request, $id = false)
    {
        if(Input::all() && User::where('id', '=', $id)) {
            $this->validation_update($request);
            $this->user = new User();
            $user = $this->user->update_user_fields(Input::all());
            if ( !isset($user['users_team_id']) || $user['users_team_id'] == '' )  {
                $user['users_team_id'] = 0;
            }
            if ( !isset($user['hourlyRate']) || $user['hourlyRate'] == '') {
                $user['hourlyRate'] = 0;
            }
            if ( isset($user['lead_id']) && $user['lead_id'] !== '') {
                $user['users_team_id'] = $user['lead_id'];
            }
            if ( !isset($user['projects']) || $user['projects'] == '' )  {
                $user['projects'] = 0;
            }
            if(isset($user['projects']) && $user['projects'] !== 0){
               $user['projects'] = implode(",",$user['projects']);
            }
            if($user['employe'] == 'Lead'){
                $leadId = User::where('id', '=', $id)->first()['attributes']['users_team_id'];
                if($leadId == $id){
                    $user['users_team_id'] = $leadId;
                } else {
                    $user['users_team_id'] = $id;
                }
            }
            User::where('id', '=', $id)->update([
                'name' => $user['name'],
                'employe' => $user['employe'],
                'users_team_id' => $user['users_team_id'],
                'hourly_rate' => $user['hourlyRate'],
                'projects' => $user['projects']
            ]);

            return redirect('/user/all');
        }

        $user = DB::table('users')->where('id', '=', $id)->first();
        $leadActive;
        $projectActive;
        
        if($user->employe == 'Developer' || $user->employe == 'Lead'){
            $leadActive = DB::table('users')->where('id', '=', $user->users_team_id)->first();
        }
        if($user->projects !== ''){
          $projectActive = explode(',', $user->projects);
        }
        $teams = DB::table('teams')->get();
        //SN 05/08/2017: updated below code for get options in alphabetical order
		$projects = DB::table('Project')->orderBy('project_name','asc')->get();
        $leads = DB::table('users')->where('employe','=','lead')->get();
        return view('auth.update_user', compact('user', 'teams', 'leadActive','projectActive','projects','leads' ));
    }
    /*
     * delete user
     * $id - user id
     * */
    public function delete($id)
    {
        DB::table('teams')->where('teams_lead_id', '=', $id)
            ->update(['teams_lead_id' => 0]);
        User::where('id', '=', $id)->delete();

        DB::table('tasks')->where('assign_to', '=', $id)
            ->update(['assign_to' => 0]);

        return redirect('/user/all');
    }

    protected function validation_create ($request)
    {
        $this->validate($request, [
            'name' => 'required|min:2|max:30',
            'email' => 'required|unique:users|email',
            'employe' => 'required|max:20',
            'hourlyRate' => 'numeric'
        ], [
            'employe.required' => 'The User type is required'
        ]);
    }

    protected function validation_update ($request)
    {
        $this->validate($request, [
            'name' => 'required|min:2|max:30',
            'employe' => 'required|max:20',
            'hourlyRate' => 'numeric'
        ], [
            'employe.required' => 'The User type is required'
        ]);
    }

    protected function password_generate()
    {
        $chars = 'qSwDerfRtyuiopasdfghjklmnbvcxz';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < 10; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }

        return substr(md5(rand(1, 10000) . 'pass' . $string), rand(0 , 6), 6);
    }
}
