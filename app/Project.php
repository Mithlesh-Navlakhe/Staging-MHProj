<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'Project';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'lead_id',
        'project_name',
        'hourly_rate',
        'notes'
    ];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function task()
    {
        return $this->hasMany('App\task');
    }

    public function track()
    {
        return $this->hasMany('App\TimeTrack');
    }

    public function track_log()
    {
        return $this->hasMany('App\TimeLog');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'lead_id');
    }
}