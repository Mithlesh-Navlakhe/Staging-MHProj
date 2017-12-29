<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    static function getId($mail)
    {

        $id = DB::table('users')->where('email', $mail)->value('id');

    //    var_dump($id);

        return $id;
    }


}
