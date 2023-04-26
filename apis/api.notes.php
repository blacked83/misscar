<?php
session_start();
date_default_timezone_set('America/Guayaquil');

require_once __DIR__ . "/../config.php";
require_once FUNCTIONS_URL . "globals.php";
require_once MODELS_URL . "model.notes.php";
$notes = new notesModel();

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        switch($_GET["action"]){
            case "getnotes":
                $resp = $notes->getNotes($_SESSION["user"]['id']);
                if(isset($resp["status"]) && $resp["status"] == "fail process")
                    header("HTTP/1.0 400 " . $resp["state"]);
                echo json_encode($resp);
                break;
            default:
                header("HTTP/1.0 400 enviroment error");
                die("enviroment error");
                break;
        }
        break;
    case 'POST':
        switch($_POST["action"]){
            case "addnote":
                if(empty($_POST["score"]) || intval($_POST["score"]) < 0 || intval($_POST["score"]) > 10 || empty($_POST["matter"])){
                    header("HTTP/1.0 400 enviroment error");
                    die("enviroment error");
                }
                if(empty($_POST['id'])){
                    $resp = $notes->addNote($_POST["matter"], $_POST["score"], $_SESSION["user"]['id']);
                }else{
                    $resp = $notes->editNote(intval($_POST["id"]), $_POST["score"]);
                }
                if(isset($resp["status"]) && $resp["status"] == "fail process")
                    header("HTTP/1.0 400 " . $resp["state"]);
                echo json_encode($resp);
                break;
            case "delnote":
                if(empty($_POST["id"])){
                    header("HTTP/1.0 400 enviroment error");
                    die("enviroment error");
                }
                $resp = $notes->delNote($_POST["id"]);
                if(isset($resp["status"]) && $resp["status"] == "fail process")
                    header("HTTP/1.0 400 " . $resp["state"]);
                echo json_encode($resp);
                break;
            default:
                header("HTTP/1.0 400 enviroment error");
                die("enviroment error");
                break;
        }
        break;
    default:
        header("HTTP/1.0 400 enviroment error");
        die("invalid method");
        break;
}

?>