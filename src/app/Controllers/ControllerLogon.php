<?php

namespace MyFW\App\Controllers;

use MyFW\app\Models\Role;
use MyFW\app\Models\User;
use MyFW\core\Config;
use MyFW\core\Context;
use MyFW\core\Controller;

class ControllerLogon extends Controller
{

    public function __construct(Context $env)
    {
        parent::__construct($env);
    }

    public function defaut()
    {
        echo $this->_env->twig->render('Logon.defaut.twig', $this->_env->displayData());
    }

    /**
     * Création d'un nouvel utilisateur
     */
    public function subscribe()
    {
        //Mettre un rôle par défaut : le dernier niveau
        $last_level=Role::orderBy('level', 'DESC')->get();

        // Ajouter les info du formulaire
        //print_r($this->_env->request->getArguments()['data']);
        $user = User::create(
            [ 'firstname' => $this->_env->request->getArguments()['data']['userFirstname'],
              'lastname' => $this->_env->request->getArguments()['data']['userLastname'],
              'email' => $this->_env->request->getArguments()['data']['userEmail'],
              'pass' => password_hash($this->_env->request->getArguments()['data']['userPwd'], PASSWORD_DEFAULT),
              'role_id' => $last_level[0]->id ]
        );
        $user->save();
        // mettre le message Success dans la session
        //envoyer sur /login pour que l'utilisateur se connecte
        $this->redirectLogin('success',"Félicitations, votre compte a été créé");
    }
}

//





