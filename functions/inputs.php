<?php
function PHP_Inputs(){
    $_FILES = array();
    $formData = "Content-Disposition: form-data;";
    $inputs = file_get_contents('php://input');
    // Se valida que existan entradas al sistema
    if(strlen(trim($inputs)) > 0){
        // Se valida que sea un formato json
        $data = json_decode($inputs, true);
        if(!empty($data))
            return $data;
        // En caso de ser formato form-data
        if(strpos($inputs, $formData) > -1){
            $aux = explode($formData, $inputs); // Se divide en líneas el contenido
            $formID = trim(str_replace(chr(13).chr(10), null, $aux[0])); // Se obtiene el id del formulario form-data
            $inputs = str_replace($formID."--".chr(13).chr(10), null, $inputs); // Se elimina el cierre del formulario
            $aux = explode($aux[0], $inputs); // Se divide el contenido en variables
            $data = ""; // Variable de almacenamiento de inputs
            foreach($aux as $env){
                if(strpos($env, "Content-Type:") === false){
                    // Si no es un archivo
                    $auxEnv = str_replace('"', "=", $env); // Se elimina las comillas dobles
                    $auxEnv = str_replace("$formData name==", null, $auxEnv); // Se elimina el contenido innecesario
                    $auxEnv = str_replace(chr(13).chr(10), null, $auxEnv); // se unifican las líneas
                    $data .= strlen($data) == 0 ? $auxEnv : "&$auxEnv"; // se añade a la avariable de almacenamiento
                }else{
                    // Si es un archivo
                    $lines = explode(chr(13).chr(10), $env); // Se divide el contenido en líneas
                    // Se formatea la primera línea con el nombre de la variable y el nombre del archivo
                    $firstLine = str_replace("$formData ", null, $lines[0]);
                    $firstLine = str_replace('"', null, $firstLine);
                    $firstLine = str_replace('filename=', null, $firstLine);
                    $firstLine = str_replace('name=', null, $firstLine);
                    $firstLine = explode("; ", $firstLine);
                    // Se anexa a la variable $_FILES el archivo
                    $_FILES[$firstLine[0]] = array();
                    $_FILES[$firstLine[0]]["name"] = $firstLine[1];
                    $_FILES[$firstLine[0]]["type"] = trim(str_replace("Content-Type: ", null, $lines[1]));
                    // Se depura solo el contenido del archivo
                    array_shift($lines); // Se Elimina la línea de Form-Data
                    array_shift($lines); // Se Elimina la línea del Mime
                    array_shift($lines); // Se Elimina la línea de separación
                    $tlines = count($lines) - 1;
                    // Si la última línea está vacía se depura
                    if(strlen(trim($lines[$tlines])) == 0)
                        array_pop($lines);
                    $lines = implode(chr(13).chr(10), $lines); // Se unifica el código del archivo
                    // Se genera el archivo temporal
                    $tmpFile = tempnam(sys_get_temp_dir(), "php"); // Se genera el nombre del archivo temporal
                    chmod($tmpFile, 0777); // Se permite el uso por todos los usuarios
                    $fp = fopen($tmpFile, "w"); // Se abre el archivo en modo escritura
                    fwrite($fp, $lines); // se inserta el contenido del archivo
                    fclose($fp); // Se cierra el archivo
                    $fp = fopen($firstLine[1], "w");
                    fwrite($fp, $lines);
                    fclose($fp);
                    // Se anexa las propiedades faltantes
                    $_FILES[$firstLine[0]]["tmp_name"] = $tmpFile;
                    $_FILES[$firstLine[0]]["error"] = UPLOAD_ERR_OK;
                    $_FILES[$firstLine[0]]["size"] = filesize($tmpFile);
                }
            }
            parse_str($data, $data);
            return $data;
        }else{
            // En caso de ser formato x-www-form-urlencoded
            parse_str(urldecode($inputs), $data);
            return $data;
        }
    }else{
        return array(); // Si uno existen entradas se retorna un array vacio
    }
}


?>