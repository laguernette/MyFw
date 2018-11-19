<?php
/**
 * Created by PhpStorm.
 * User: lydie
 * Date: 05/11/2018
 * Time: 13:26
 */

namespace MyFW\core;


use MyFW\app\Models\User;

class Session
{

    public function __construct(){
        session_start();
    }

    public function closeSession(){
        //supprimer cookie de session
        $_SESSION=array();
        //détruire la session
        session_destroy();
    }

    public function register(User $user){
        $_SESSION['user_id']=$user->id;
        $_SESSION['role_id']=$user->role_id;
    }

    public function getRoleId(){
        return $this->getSessionItem('role_id');
    }

    public function getUserId(){
        return $this->getSessionItem('user_id');
    }

    public function getSessionItem($name){
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        } else {
            return false;
        }
    }

    public function setSession($name, $value){
        $_SESSION[$name]=$value;
    }

    public function unsetSession($name){
        unset($_SESSION[$name]);
    }

    public function issetSession($name){
        return isset($_SESSION[$name]);
    }

}