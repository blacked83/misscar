<?php

function requireFuntions($path){
    if(is_dir($path)){
        $directory = opendir($path);
        while($element = readdir($directory))
            if($element != "." && $element != "..")
                if(is_file("$path/$element")){
                    require_once "$path/$element";
                }else{
                    requireFuntions("$path/$element");
                }
    }
}

requireFuntions(__DIR__);

?>