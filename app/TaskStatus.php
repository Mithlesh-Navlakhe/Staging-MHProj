<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    //
	protected $table = 'task_status';
	
	protected $fillable = [
        'name',
        'description',
        'status_order'
    ];
	
    protected $guarded = array();
}
