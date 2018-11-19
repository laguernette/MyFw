<?php

namespace MyFW\app\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table='roles';
    public $timestamps = false;

    protected $fillable=['name','level'];
    protected $guarded=['id'];

    public function users()
    {
        return $this->hasMany('MyFW\App\Models\User');
    }

    public function permissions()
    {
        return $this->belongsToMany('MyFW\App\Models\Permission');
    }
}