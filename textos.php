<?php
    function obtenerTextos($tabla,$indice) {
        //$textos['TAREAS']['nombre']="Título";
        //$textos['TAREAS']['descripción']="Descripción";
        //$textos = file("./textos.txt");
        $fp = fopen("./textos.txt", "r");
        while(!feof($fp)) {
            $linea = fgets($fp);
            //echo $linea;
            $arr = preg_split("/[\t]/",$linea);
            if (isset($arr[0]) && isset($arr[1]) && ($arr[0]==$tabla) && ($arr[1]==$indice)) {
                fclose($fp);
                return $arr[2];
            }
        }
        return -1;
    }
?>