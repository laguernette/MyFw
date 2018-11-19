<?php

namespace MyFW\app\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table='users';
    public $timestamps = false;

    protected $fillable=['firstname','lastname','email','pass','role_id'];
    protected $guarded=['id'];


    public function role()
    {
        return $this->belongsTo('MyFW\App\Models\Role');
    }

    /*
    public function roles(){
        return $this->belongsTo('MyFW\App\Role');
    }
    */
}