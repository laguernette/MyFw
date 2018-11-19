<?php
/**
 * Created by PhpStorm.
 * User: lydie
 * Date: 05/11/2018
 * Time: 14:41
 */

namespace MyFW\core;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MyFW\app\Models\Droits;
use MyFW\app\Models\User;

class Controller
{
    protected $_env;

    public function __construct(Context $env)
    {
        $this->_env = $env;
        $this->_env->setData('title',Config::APPTITLE);
        $this->_env->setData('nomApp',Config::APPNAME);

        $this->_env->setData('userFirstname',$this->_env->session->getSessionItem('userFirstname'));

        $this->_env->setData('isConnected',$this->_env->session->getSessionItem('isConnected'));
        $this->_env->setData('isAuthorized',$this->_env->session->getSessionItem('isAuthorized'));

        if($this->_env->session->issetSession("msgType")){
            $this->_env->setData('msgType',$this->_env->session->getSessionItem('msgType'));
            $this->_env->setData('msgContent',$this->_env->session->getSessionItem('msgContent'));
            $this->_env->session->unsetSession('msgType');
            $this->_env->session->unsetSession('msgContent');
        }
    }

    public function redirect($url,$msgType,$msgContent){
        $this->_env->session->setSession('msgType', $msgType);
        $this->_env->session->setSession('msgContent',$msgContent);
        header('Location: '.$url);
    }

    public function redirectLogin($msgType,$msgContent){
        $this->redirect('/login',$msgType,$msgContent);
    }

}