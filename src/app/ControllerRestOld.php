<?php

namespace MyFW\App;

use \MyFW\Core\Request;

class ControllerRestOld
{

    private $_request;
    // temporaire
    private $_pdo;

    //

    public function __construct(Request $r)
    {
        $this->_request = $r;
        $this->_pdo = new \PDO("mysql:host=localhost;dbname=rest;charset=utf8", "rest", "tser");
    }

    function users($params = NULL)
    {
        // 8 cas REST
        switch ($this->_request->getMethod()) {
            case 'GET':
                if (is_null($params)) {

                    echo 'List all users';
                    $query = $this->_pdo->query("SELECT * FROM users");
                    echo '<hr>';
                    echo '<pre>';
                    print_r($query->fetchAll());
                    echo '</pre>';

                } else {
                    // PDO obligatoirement
                    // 1. try catch
                    // 2. requête préparée (nommer tous les champs, finir par un ;)
                    // 3. liaison des paramères
                    try {
                        echo 'List user #' . $params;
                        $sql = 'SELECT firstname, lastname FROM users WHERE id = :id;';
                        $stmt = $this->_pdo->prepare($sql);
                        $stmt->bindParam(':id', $params, \PDO::PARAM_INT);
                        $stmt->execute();
                        echo '<hr>';
                        echo '<pre>';
                        print_r($stmt->fetch());
                        echo '</pre>';
                    } catch (\Error $e) {
                        echo 'Erreur';
                    }

                }
                break;
            case 'POST':
                break;
            case 'PUT':
                break;
            case 'PATCH':
                break;
            case 'DELETE':
                if (is_null($params)) {
                    echo 'Delete all users';
                } else {
                    echo 'Delete user #' . $params;
                }
                break;
        }


    }

    function defaut()
    {
        echo 'defaut action';
    }

}
