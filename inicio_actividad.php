<?php
	function inicio_actividad() {
		include('conectar.php');
		$fecha=date("Y-m-d H:i:s");
		$tabla = 'inicio_actividad';
		$query="UPDATE $tabla set  fecha='$fecha'";
		if (! $mysqli->query($query)) {
			error_log("ERROR: No se pudo ejecutar $query. " . $mysqli->error,0);		
		}
		$mysqli->close();
	}
	function obtener_fecha() {
		include('./conectar.php');
		$query = "select fecha from gt.inicio_actividad";
		if ($result = $mysqli->query($query)) {
	    		$row = $result->fetch_array()[0];
		}
		$mysqli->close();
		return $row;
	}
?>
