<?php
require_once __DIR__ . '/vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as Capsule;
use \MyFW\App\Users;
use \MyFW\App\Roles;

// démarage de l'ORM Eloquent
$capsule = new Capsule;
$capsule->addConnection(array(
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'rest',
    'username' => 'rest',
    'password' => 'tser',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
));
$capsule->bootEloquent();

//$u = Users::find(1);

/*
$u->firstname=ucfirst(strtolower($u->firstname));
$u->lastname=strtoupper($u->lastname);

echo $u->firstname;
echo '<br>';
echo $u->lastname;

$u->save();

echo '<hr>';
$users = Users::all();
foreach ($users as $user) {
    echo $user->firstname;
    echo '<br>';
}
*/
/*
$users = Users::all();
foreach ($users as $user) {
    echo $user->firstname.' '.$user->lastname;
    echo '<br>';
}
echo '<hr>';
$users = Users::all();
foreach ($users as $user) {
    $user->firstname=ucfirst(strtolower($user->firstname));
    $user->lastname=strtoupper($user->lastname);
    echo $user->firstname.' '.$user->lastname;
    echo '<br>';
    $user->save();
}
*/

/*
$users = Users::all();

foreach ($users as $user) {
    echo $user->firstname.' '.$user->lastname;
    echo '<br>';
}
echo '<hr>';

//trier la BDD
$users = Users::orderBy('firstname','desc')->get();
foreach ($users as $user) {
    echo $user->firstname.' '.$user->lastname;
    echo '<br>';
}
echo '<hr>';

$users = Users::where('firstname','Magali')->get();
foreach ($users as $user) {
    echo $user->firstname.' '.$user->lastname;
    echo '<br>';
}

echo '<hr>';
echo Users::all()->toJson();
*/

/*
$users = Users::all();
foreach ($users as $user) {
    echo $user->firstname.' '.$user->lastname;
    echo '<br>';
    echo Roles::find($user->roles_id)->name;
    echo '<br>';
    echo '<br>';
}
*/

/*
$roles=Roles::all();

foreach($roles as $role){
    echo $role->name;
    echo '<br>';
    foreach ($role->users as $user){
        echo $user->firstname.' '.$user->lastname;
        echo '<br>';
    }
}
*/

/*
//Liste des utiliateurs avec leur rôle
$users = Users::all();
foreach ($users as $user) {
    echo $user->firstname.' '.$user->lastname;
    echo '<br>';
    echo $user->role->name;
    echo '<br>';
    echo '<br>';
}
*/

/*
//on recherche les admin et les membres
$roles=Roles::whereIn('name',['admin','member'])->get();

foreach($roles as $role){
    foreach($role->users as $user){
        echo $user->lastname;
        echo '<br>';
    }
}
*/

/*
//On veut tous les users sauf les membres:
$users = Users::all();
$users = $users->reject(function($user){
    return $user->role->name =='member';
});

foreach($users as $user){
    echo $user->firstname.' '.$user->lastname;
    echo '<br>';
}
*/

/*
//ajouter un élement dans la BDD :
$role = new Roles();
$role->name='guest';
$role->save();
*/

/*
//liste de tous les rôles :
echo Roles::all()->toJson();
*/

/*
//liste des roles qui ont plus d'un user :
echo Roles::has('users','>',1)->get()->toJSON();
//liste des roles qui n'ont pas d'utilisateur :
echo Roles::has('users','=',0)->get()->toJSON();
*/






