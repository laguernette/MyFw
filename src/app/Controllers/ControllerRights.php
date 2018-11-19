<?php

namespace MyFW\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MyFW\app\Models\Permission;
use MyFW\app\Models\Permission_role;
use MyFW\app\Models\Role;
use MyFW\app\Models\User;
use MyFW\Core\Context;
use MyFW\core\Controller;

class ControllerRights extends Controller
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
            $roles = Role::where('level', '>=', $level)->orderBy('level', 'ASC')->get();

            //Récupérer la liste des niveaux
            $list_level = $this->initListLevel($roles);

            //Récupérer tous les droits correspondants
            //$list_rights=array();
            foreach ($roles as $role) {
                foreach ($role->permissions as $right) {
                    //$list_rights[]=$right;
                    $list_permission[] = $right->id;
                }
            }
            //Récupérer toutes les permissions correspondantes par ordre alphabétique
            if ($this->_env->isAdmin($role_id)) {
                //Si Admin : il a tous les droits
                $list_permission = Permission::orderBy('label', 'ASC')->get();
            } else {
                //Si pas Admin : liste des permissions accessibles - sans doublons
                $list_permission = Permission::whereIn('id', $list_permission)->orderBy('label', 'ASC')->get();
            }

            $message = 'Vous êtes : ' . Role::where('id', $role_id)->first()->name;

            $this->_env->setData('message', $message);
            $this->_env->setData('user_role_level', $level);
            $this->_env->setData('listLevel', $list_level);
            $this->_env->setData('listPermissions', $list_permission);
            $this->_env->setData('listRoles', $roles);
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    public function defaut()
    {
        //vérifier s'il a les droits pour cette page
        if($this->_env->isAuthorized('rights.defaut')) {
            echo $this->_env->twig->render('rights.defaut.twig', $this->_env->displayData());
        }else {
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }

    }

    /**
     * Ajouter un rôle
     */
    public function createRole(){
        if($this->_env->isAuthorized('rights.create')) {
            $role = Role::firstOrCreate(
                ['name'=>$this->_env->request->getArguments()['data']['role_name'],
                 'level'=>$this->_env->request->getArguments()['data']['role_level']
                ]);
            $role->save();
            $this->redirect('/rights','success','Le rôle '.$role->name.' a été créé');
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    /**
     * Modifier le niveau d'un rôle
     */
    public function modifyRole(){
        if($this->_env->isAuthorized('rights.modify')) {
            $role = Role::findOrFail($this->_env->request->getArguments()['data']['role_id']);
            $role->level = $this->_env->request->getArguments()['data']['role_level'];
            $role->name = $this->_env->request->getArguments()['data']['role_name'];
            $role->save();
            $this->redirect('/rights','success','Le rôle '.$role->name.' a été modifié');
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    /**
     * Supprimer un rôle
     */
    public function deleteRole(){
        if($this->_env->isAuthorized('rights.delete')) {
            $role = Role::where('id', $this->_env->request->getArguments()['data']['role_id'])->first();
            $old_name=$role->name;
            $role->delete();
            $this->redirect('/rights','success','Le rôle '.$old_name.' a été supprimé');
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }

    }

    /**
     * Supprimer un droit
     * On passe de OUI à NON, donc on détache le droit
     */
    public function deleteRights(){
        if($this->_env->isAuthorized('rights.defaut')) {
            $role_id=$this->_env->request->getArguments()['data']['role_id'];
            $permission_id=$this->_env->request->getArguments()['data']['permission_id'];
            $right=Role::find($role_id);
            $right->permissions()->detach($permission_id);
            header('Location: /rights');
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }

    /**
     * Ajouter un droit
     * On passe de NON à OUI, donc on attache le droit
     */
    public function addRights(){
        if($this->_env->isAuthorized('rights.defaut')) {
            $role_id=$this->_env->request->getArguments()['data']['role_id'];
            $permission_id=$this->_env->request->getArguments()['data']['permission_id'];
            $right=Role::find($role_id);
            $right->permissions()->attach($permission_id);
            $right->save();
            header('Location: /rights');
        }else{
            $this->redirectLogin('danger',"Vous n'êtes pas autorisés à accéder à cette page. Connectez-vous");
        }
    }


    /**
     * Faire la liste des niveaux inférieurs à ceux de l'utilisateur connecté
     * @param $roles
     * @return array
     */
    private function initListLevel($roles){
        $first_level=$roles[0]->level;
        $last_level=$roles[count($roles)-1]->level;
        $list_level=[];
        //Le dernier niveau ne peut pas créer de niveau en dessous de lui
        if($first_level!=$last_level) {
            for($i=$first_level;$i<=$last_level;$i++){
                //Ex: un user de niveau 2, pourra ajouter les 3,4,... (+ un niveau supp non existant)
                $list_level[] = $i+1;
            }
        }
        return $list_level;
    }
}















