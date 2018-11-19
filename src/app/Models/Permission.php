<?php
/**
 * Created by PhpStorm.
 * User: lydie
 * Date: 05/11/2018
 * Time: 16:17
 */

namespace MyFW\app\Models;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table='permissions';
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany('MyFW\App\Models\Role');
    }
}