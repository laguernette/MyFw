<?php
/**
 * Created by PhpStorm.
 * User: lydie
 * Date: 27/09/2018
 * Time: 08:51
 */
namespace MyFW\core;

use \Illuminate\Database\Capsule\Manager as Capsule;
use MyFW\app\Models\Permission;
use MyFW\app\Models\Permission_role;

class Context
{
    //Représente la Requête HTTP
    public $request = NULL;
    //Accès à la BDD
    public $dao = NULL;
    //Accès à la configuration
    public $config = NULL;
    //Générateur de Vues
    public $twig = NULL;
    //Gestionnaire de session
    public $session=NULL;

    private $data=array();

    //Initialisation de l'environnement
    public function __construct(Request $request){
        // Request
        $this->request=$request;
        //Session
        $this->session=new Session();
        // Config
        $this->config=new Config;
        // Eloquent/Capsule
        $this->dao=new Capsule;
        $this->dao->addConnection(array(
            'driver' => 'mysql',
            'host' => $this->config::DBHOST,
            'database' => $this->config::DBNAME,
            'username' => $this->config::DBUSER,
            'password' => $this->config::DBPASS,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ));
        $this->dao->bootEloquent();
        $this->dao->setAsGlobal();// permet l'appel statique
        // Template Engine/TWIG
        // Specify our Twig templates location
        $loader = new \Twig\Loader\FilesystemLoader($this->config::TEMPLATESSDIR);
        // Instantiate our Twig
        $this->twig=new \Twig\Environment($loader);
        $ref = $this;

        //Fonction dans Twig
        //Fonction isAuthorized dans Twig
        $function = new \Twig_Function('isAuthorized', function($permission) use ($ref) {
            /*
            //Récupérer le rôle
            $role=$ref->session->getRoleId();
            //On réinitialise la variable isAuthorized à false
            $ref->setData('isAuthorized',false);
            //Récupérer si authorisation
            $isAuth = $ref->isAdmin($role) | $ref->haveRights($role, $right);
            //On réinitialise la variable isAuthorized
            $ref->setData('isAuthorized',$isAuth);
            return $isAuth;
            */
            return $this->isAuthorized($permission);
        });
        $this->twig->addFunction($function);
        //Fonction isAdmin pour Twig
        $function = new \Twig_Function('isAdmin', function($role) use ($ref) {
            return $this->isAdmin($role);
        });
        $this->twig->addFunction($function);
        //Fonction haveRights pour Twig
        $function = new \Twig_Function('haveRights', function($role,$permission) use ($ref) {
            return $this->haveRights($role,$permission);
        });
        $this->twig->addFunction($function);
    }

    public function displayData(){
        //affiche les données de $data
        return $this->data;
    }

    public function setData($name,$value){
        //Modifie ou ajoute une nouvelle valeur
        $this->session->setSession($name,$value);
        if(!isset($this->data[$name])){
            $this->data+=[$name=>$value];
        }else{
            $this->data[$name]=$value;
        }
    }

    public function getData($name){
        //existe en session ?
        if($this->session->getSessionItem($name)){
            return $this->session->getSessionItem($name);
        }
        //Récupère la valeur spécifiée
        if(isset($this->data[$name])){
            return $this->data[$name];
        }else{
            return false;
        }
    }

    public function isAuthorized($permission){
        //Récupérer le rôle
        $role=$this->session->getRoleId();
        //On réinitialise la variable isAuthorized à false
        $this->setData('isAuthorized',false);
        //Récupérer si authorisation
        $isAuth = $this->isAdmin($role) | $this->haveRights($role,$permission);
        //On réinitialise la variable isAuthorized
        $this->setData('isAuthorized',$isAuth);
        return $isAuth;
    }

    public function isAdmin($role){
        //Vérifie si c'est un admin
        return $role==1;
    }

    public function haveRights($role,$permission){
        //Cherche le droit avec le rôle et la permission en paramètres
        $permission_id=Permission::where('permission', $permission)->first()->id;
        $data = Permission_role::where([
            'role_id'=> $role,
            'permission_id'=>$permission_id
        ])->get();
        return !($data->isEmpty());
    }

}
