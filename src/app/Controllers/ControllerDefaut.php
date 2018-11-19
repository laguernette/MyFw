<?php

namespace MyFW\App\Controllers;

use MyFW\app\Models\User;
use MyFW\core\Context;
use MyFW\core\Controller;

class ControllerDefaut extends Controller
{

    public function __construct(Context $env){

        parent::__construct($env);

        $message='Bienvenue sur le site des picologues !';

        //Envoyer les données dans $data
        $this->_env->setData('msg_accueil',$message);
    }


    public function defaut(){
        echo $this->_env->twig->render('Defaut.defaut.twig',$this->_env->displayData());
    }

}
