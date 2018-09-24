<?php

namespace MyFW\App;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MyFW\Core\Request;

class ControllerRestUsers
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
                if (is_null($params)) {
                    echo Users::all()->toJson();
                } else {
                    try {
                        echo Users::findOrFail($params)->toJson();
                    } catch (ModelNotFoundException $e) {
                        //echo '{"success": "fail", "message": "not found"}';
                        $data = array("success" => "fail", "message" => "not found");
                        echo json_encode($data);
                    }
                    /*
                    echo 'List user #' . $params . '<br>';
                    $u = Users::find($params);
                    if (isset($u)) {
                        echo 'firstname : ' . $u->firstname . '<br>';
                        echo 'lastname : ' . $u->lastname . '<br>';
                    }
                    */
                }
                break;
            case 'POST':
                //Ajoute une donnée dans la BDD
                $data = $this->_request->getArguments()['data'];
                $u = new Users();
                $u->firstname = $data['firstname'];
                $u->lastname = $data['lastname'];
                $u->roles_id = $data['roles_id'];
                $u->save();
                $data = array("success" => "success", "message" => "new add");
                echo json_encode($data);
                break;
            case 'PUT':
                //metttre à jour les données ou les ajouter si n'existent pas
                if (is_null($params)) {
                    $data = array("success" => "fail", "message" => "NYI ! Update all users");
                    echo json_encode($data);
                } else {
                    $data = $this->_request->getArguments()['data'];
                    try {
                        $u = Users::findOrFail($params);
                        if (isset($data['firstname'])) {
                            $u->firstname = $data['firstname'];
                        }
                        if (isset($data['lastname'])) {
                            $u->lastname = $data['lastname'];
                        }
                        if (isset($data['roles_id'])) {
                            $u->roles_id = $data['roles_id'];
                        }
                        $u->save();
                        $data = array("success" => "success", "message" => "update");
                        echo json_encode($data);
                    } catch (ModelNotFoundException $e) {
                        $u = new Users();
                        $u->id = $params;
                        $u->firstname = $data['firstname'];
                        $u->lastname = $data['lastname'];
                        $u->roles_id = $data['roles_id'];
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
                    $u = Users::findOrFail($params);
                    if (isset($data['firstname'])) {
                        $u->firstname = $data['firstname'];
                    }
                    if (isset($data['lastname'])) {
                        $u->lastname = $data['lastname'];
                    }
                    if (isset($data['roles_id'])) {
                        $u->roles_id = $data['roles_id'];
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
                    $data = array("success" => "fail", "message" => "NYI ! Delete all users");
                    echo json_encode($data);
                } else {
                    try {
                        $data = Users::findOrFail($params);
                        $data->delete();
                        $data = array("success" => "success", "message" => "delete");
                        echo json_encode($data);
                    } catch (ModelNotFoundException $e) {
                        $data = array("success" => "fail", "message" => "not found");
                        echo json_encode($data);
                    }
                }
                break;
        }
    }
    /*
    function defaut(){
        echo 'defaut action';
    }
    */
}
