<?php
//Función que permite registar un evento en archivo log
function logs($log){
    if(!file_exists(LOGS_URL))
        mkdir(LOGS_URL);
    if(empty($GLOBALS["api"])){
        $fileLog = LOGS_URL . str_replace("api.php", ".log", basename($_SERVER["SCRIPT_NAME"]));
        $fileLog = str_replace(".php", ".log", $fileLog);
    }else{
        $fileLog = LOGS_URL . $GLOBALS["api"] . ".log";
    }
    $line = "[" . date("Y/m/d h:i:s a P") . "][" . getRealIP() . "] " . $log . ".\n";
    if(@file_put_contents($fileLog, $line, FILE_APPEND) === false)
        return false;
    return true;
}
?>