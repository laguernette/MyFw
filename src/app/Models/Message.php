<?php

namespace MyFW\app\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table='messages';
    public $timestamps = false;

    protected $fillable=['title','content','user_id_writer','user_id_reader'];
    protected $guarded=['id'];

    public function reader()
    {
        return $this->belongsTo('MyFW\App\Models\User','user_id_reader');
    }

    public function writer()
    {
        return $this->belongsTo('MyFW\App\Models\User','user_id_writer');
    }
}