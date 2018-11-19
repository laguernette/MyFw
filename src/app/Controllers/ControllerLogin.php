<?php

namespace MyFW\App\Controllers;

use MyFW\app\Models\User;
use MyFW\core\Config;
use MyFW\core\Context;
use MyFW\core\Controller;

class ControllerLogin extends Controller
{

    public function defaut()
    {
        echo $this->_env->twig->render('Login.defaut.twig', $this->_env->displayData());
    }

    /**
     * Se connecter au site
     */
    public function submit()
    {
        $data = $this->_env->request->getArguments()['data'];
        $u = User::where('email', $data['email'])->first();
        if ( isset($u) && password_verify($data['password'], $u->pass) ){
            $this->_env->session->register($u);
            //Envoyer la mise à jour de la variable isConnected et userFirstname
            $this->_env->session->setSession('userFirstname',$u->firstname);
            $this->_env->session->setSession('isConnected',true);
            //envoyer sur /login/success
            header('Location: /login/success');
        }else{
            //envoyer sur /login
            $this->redirectLogin('danger',"Utilisateur ou mot de passe erroné");
        }
    }

    /**
     * Afficher la page de connexion
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *      */
    public function success(){
        //vérifier via un cookie que la connexion est OK ?
        //utiliser la méthode isConnected présente dans le controleur principal
        if($this->_env->getData('isConnected')){
            echo $this->_env->twig->render('Login.success.twig',$this->_env->displayData());
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    /**
     * Se déconnecter du site
     */
    public function logout(){
        $this->_env->session->closeSession();
        $this->redirectLogin('success',"Vous êtes déconnectés");
    }

}














