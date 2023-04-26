<?php
if(session_status() != PHP_SESSION_ACTIVE) session_start();
if(empty($_SESSION["user"])){
    header("HTTP/1.0 400 No active session");
    $data = ["status" => "fail process", "state" => "user without an active session"];
    die(json_encode($data));
}
?>