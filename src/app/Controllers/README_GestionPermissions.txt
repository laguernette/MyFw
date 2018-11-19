Gestion des permissions :

Si on veut ajouter une permission 'example.test' :

Ajouter dans la table, le nom de la permission avec son label (l'id est généré automatiquement)

Coder la permission (ici : test) dans le controller (ici : ControllerExemple)

class ControllerExample extends Controller
{
    public function test(){
        if($this->_env->isAuthorized('example.test')) {
            //votre code
        }else{
            //votre code de redirection ou autre
        }
}

L'administrateur a TOUS les droits, il n'est donc pas utile de le lui rajouter.
Ensuite, vous n'avez plus qu'à aller sur le site, dans l'onglet "Gestion des droits", pour assigner cette nouvelle permission à vos utilisateurs