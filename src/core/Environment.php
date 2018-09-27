<?php
/**
 * Created by PhpStorm.
 * User: lydie
 * Date: 27/09/2018
 * Time: 08:51
 */
namespace MyFW\core;

use \Illuminate\Database\Capsule\Manager as Capsule;

class Environment
{
    //Représente la Requête HTTP
    public $request = NULL;
    //Accès à la BDD
    public $capsule = NULL;
    //Accès à la configuration
    public $config = NULL;
    //Générateur de Vues
    public $renderer = NULL;

    //Initialisation de l'environnement
    public function __construct(Request $request){
        $this->request=$request;
        $this->config=new Config;
        $this->capsule=new Capsule;
        $this->capsule->addConnection(array(
            'driver' => 'mysql',
            'host' => $this->config::DBHOST,
            'database' => $this->config::DBNAME,
            'username' => $this->config::DBUSER,
            'password' => $this->config::DBPASS,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ));
        $this->capsule->bootEloquent();
    }
}
