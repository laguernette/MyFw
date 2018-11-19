<?php

namespace MyFW\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MyFW\app\Models\Role;
use MyFW\app\Models\User;
use MyFW\Core\Context;
use MyFW\core\Controller;

class ControllerUsers extends Controller
{

    public function __construct(Context $env)
    {
        parent::__construct($env);

        if($this->_env->getData('isConnected')) {
            //Récupérer le role_id de l'utilisateur connecté
            $role_id = $this->_env->session->getRoleId();


            //Récupérer le level de l'utilisateur connecté en fonction du role_id
            $level = Role::where('id', $role_id)->first()->level;

            //Récupérer tous les utilisateurs ayant un level >= à celui de l'utilisateur connecté
            $roles = Role::where('level', '>=', $level)->get();

            //Récupérer tous les users correspondants
            $list_users = array();
            foreach ($roles as $role) {
                foreach ($role->users as $user) {
                    $list_users[] = $user;
                }
            }

            //Envoyer les informations dans $data
            $this->_env->setData('listUsers', $list_users);
            $this->_env->setData('listRoles', $roles);
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    public function defaut()
    {
        //vérifier s'il a les droits pour cette page
        if($this->_env->isAuthorized('users.defaut')) {
            //Nombre d'admin
            $this->_env->setData('nbAdmin',User::where('role_id',1)->get()->count());
            //Go go go sur users.defaut !
            echo $this->_env->twig->render('User.defaut.twig', $this->_env->displayData());
        }else {
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }

    }

    public function reinit_password(){
        if($this->_env->isAuthorized('users.defaut')) {
            $user = User::where('id', $this->_env->request->getArguments()['data']['id'])->first();
            $user->pass = password_hash("Azerty123", PASSWORD_DEFAULT);
            $user->save();
            $this->redirect('/users','success',"Votre mot de passe a été réinitialisé");
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    public function change_role(){
        if($this->_env->isAuthorized('users.defaut')) {
            $user = User::where('id', $this->_env->request->getArguments()['data']['id'])->first();
            $user->role_id = $this->_env->request->getArguments()['data']['role_id'];
            $user->save();
            $this->redirect('/users','success','Nouveau rôle : '.$user->role->name);
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    public function delete(){
        if($this->_env->isAuthorized('users.defaut')) {
            $user = User::where('id', $this->_env->request->getArguments()['data']['id'])->first();
            $old_user=$user->lastname.' '.$user->firstname;
            $old_user_id=$user->id;
            $user->delete();
            //Si l'user a supprimé son compte, on le déconnecte
            if($old_user_id == $this->_env->session->getUserId()){
                $this->_env->session->closeSession();
                $this->redirectLogin('success',"Vous êtes déconnectés");
            }else{
                $this->redirect('/users','success','L\'utilisateur '.$old_user. ' a été supprimé');
            }
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

}
