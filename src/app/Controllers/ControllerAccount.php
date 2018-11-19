<?php

namespace MyFW\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MyFW\app\Models\Role;
use MyFW\app\Models\User;
use MyFW\Core\Context;
use MyFW\core\Controller;

class ControllerAccount extends Controller
{

    public function __construct(Context $env)
    {
        parent::__construct($env);

        if($this->_env->getData('isConnected')) {
            //Les informations de l'utilisateur : lastname, firstname, email et rôle
            //Récupérer le user_id de l'utilisateur connecté
            $user_id = $this->_env->session->getUserId();
            $user = User::where('id', $user_id)->first();

            $this->_env->setData('user', $user);
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    public function defaut()
    {
        //vérifier s'il a les droits pour cette page
        if($this->_env->getData('isConnected')) {
            //Go go go sur account.defaut !
            echo $this->_env->twig->render('Account.defaut.twig', $this->_env->displayData());
        }else {
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    /**
     * Mettre à jour les données de l'utilisateur
     */
    public function updateUser()
    {
        //vérifier s'il a les droits pour cette page
        if($this->_env->getData('isConnected')) {
            $user_id = $this->_env->request->getArguments()['data']['id'];
            $user = User::where('id', $user_id)->first();
            $user->lastname=$this->_env->request->getArguments()['data']['userLastname'];
            $user->firstname = $this->_env->request->getArguments()['data']['userFirstname'];
            $user->email = $this->_env->request->getArguments()['data']['userEmail'];
            //S'il y a une donnée, alors hasher le nouveau pwd
            $userPwd = $this->_env->request->getArguments()['data']['userPwd'];
            if(! empty($userPwd)){
                $user->pass = password_hash($userPwd,PASSWORD_DEFAULT);
            }
            $user->save();
            $this->redirect('/account','success',"Votre compte a été mis à jour");
        } else {
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }
}
