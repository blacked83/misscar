<?php
session_start();
date_default_timezone_set('America/Guayaquil');

if(empty($_POST["action"])){
    header("HTTP/1.0 400 enviroment error");
    die("enviroment error");
}


require_once __DIR__ . "/../config.php";
require_once FUNCTIONS_URL . "globals.php";
require_once MODELS_URL . "model.login.php";
$login = new loginModel();

switch($_POST["action"]){
    case "register":
        if(empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["phone"]) || empty($_POST["birthday"])){
            header("HTTP/1.0 400 enviroment error");
            die("enviroment error");
        }
        $resp = $login->register($_POST["firstname"], $_POST["lastname"], $_POST["email"], $_POST["phone"], $_POST["birthday"], $_POST["password"]);
        if(isset($resp["status"]) && $resp["status"] == "fail process")
            header("HTTP/1.0 400 " . $resp["state"]);
        echo json_encode($resp);
        break;
    case "login":
        if(empty($_POST["email"]) || empty($_POST["password"])){
            header("HTTP/1.0 400 enviroment error");
            die("enviroment error");
        }
        $resp = $login->login($_POST["email"], $_POST["password"]);
        if(isset($resp["status"]) && $resp["status"] == "fail process")
            header("HTTP/1.0 400 " . $resp["state"]);
        echo json_encode($resp);
        break;
    case "logout":
        if(empty($_SESSION["user"])){
            header("HTTP/1.0 400 No active session");
            die("user without an active session");
        }
        unset($_SESSION["user"]);
        session_destroy();
        $resp = ["status" => "success", "state" => "user logged out"];
        echo json_encode($resp);
        break;
    default:
        header("HTTP/1.0 400 enviroment error");
        die("enviroment error");
        break;
}

?>