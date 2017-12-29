<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'Clients';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name',
        'company_address',
        'website',
        'contact_person',
        'email',
        'phone_number'
    ];

    public function project()
    {
        return $this->hasMany('App\Project');
    }
}