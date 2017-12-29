<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpdateColumn extends Model
{
    //
	protected $table = 'update_column';
	
	protected $fillable = [
        'user_id',
        'column_id',
		'report_name'
    ];
	
    protected $guarded = array();
}
