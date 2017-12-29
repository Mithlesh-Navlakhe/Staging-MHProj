<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\MyResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employe',
        'users_team_id',
        'hourly_rate',
        'projects'
    ];

    private $no_team = [
        'Supervisor',
        'Admin',
        'HR Manager',
        'QA Engineer',
		'Super Admin'
    ];

    private $no_hourle_rate = [
        'Admin',
        'HR Manager'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));
    }

    public function task()
    {
        return $this->hasMany('App\Task', 'id', 'assign_to');
    }

    public function update_user_fields( $data )
    {
        if( in_array($data['employe'], $this->no_team ) ) {
            $data['users_team_id'] = 0;
			$data['projects'] = 0;
            $data['team_name'] = 'no_team';
        };

        if( in_array($data['employe'], $this->no_hourle_rate ) ) {
            $data['hourlyRate'] = 0;
        };

        return $data;
    }
}
