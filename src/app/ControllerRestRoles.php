<?php

namespace MyFW\App;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MyFW\Core\Request;

class ControllerRestRoles
{

    private $_request;

    public function __construct(Request $r)
    {
        $this->_request = $r;
    }

    function defaut($params = NULL)
    {
        // 8 cas REST
        header('Content-Type: application/json');
        switch ($this->_request->getMethod()) {
            case 'GET':
                //Affiche les données de la BDD
                if (is_null($params)) {
                    echo Roles::all()->toJson();
                } else {
                    try {
                        echo Roles::findOrFail($params)->toJson();
                    } catch (ModelNotFoundException $e) {
                        //echo '{"success": "fail", "message": "not found"}';
                        $data = array("success" => "fail", "message" => "not found");
                        echo json_encode($data);
                    }
                }
                break;
            case 'POST':
                //Ajoute une donnée dans la BDD
                $data = $this->_request->getArguments()['data'];
                $u = new Roles();
                $u->name = $data['name'];
                $u->save();
                $data = array("success" => "success", "message" => "new add");
                echo json_encode($data);
                break;
            case 'PUT':
                //metttre à jour les données ou les ajouter si n'existent pas
                if (is_null($params)) {
                    $data = array("success" => "fail", "message" => "NYI ! Update all roles");
                    echo json_encode($data);
                } else {
                    $data = $this->_request->getArguments()['data'];
                    try {
                        $u = Roles::findOrFail($params);
                        if (isset($data['name'])) {
                            $u->name = $data['name'];
                        }
                        $u->save();
                        $data = array("success" => "success", "message" => "update");
                        echo json_encode($data);
                    } catch (ModelNotFoundException $e) {
                        $u = new Roles();
                        $u->id = $params;
                        $u->name = $data['name'];
                        $u->save();
                        $data = array("success" => "success", "message" => "new add");
                        echo json_encode($data);
                    }
                }
                break;
            case 'PATCH':
                //metttre à jour les données
                $data = $this->_request->getArguments()['data'];
                try {
                    $u = Roles::findOrFail($params);
                    if (isset($data['name'])) {
                        $u->name = $data['name'];
                    }
                    $u->save();
                    $data = array("success" => "success", "message" => "update");
                    echo json_encode($data);
                } catch (ModelNotFoundException $e) {
                    $data = array("success" => "fail", "message" => "not found");
                    echo json_encode($data);
                }
                break;
            case 'DELETE':
                if (is_null($params)) {
                    $data = array("success" => "fail", "message" => "NYI ! Delete all roles");
                    echo json_encode($data);
                } else {
                    try {
                        $data = Roles::findOrFail($params);
                        $users = Users::where('roles_id', $params)->get();
                        if (count($users) > 0) {
                            $data = array("success" => "fail", "message" => "Too users. Dont' delete !");
                        } else {
                            $data->delete();
                            $data = array("success" => "success", "message" => "delete");
                        }
                        echo json_encode($data);
                    } catch (ModelNotFoundException $e) {
                        $data = array("success" => "fail", "message" => "not found");
                        echo json_encode($data);
                    }
                }
                break;
        }
    }
}
