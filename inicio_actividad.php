<?php
	date_default_timezone_set ( "America/Argentina/Buenos_Aires" );
	function inicio_actividad($userid) {
		if (isset($userid)) {
			include('conectar.php');
			$fecha=date("Y-m-d H:i:s");
			$tabla = 'inicio_actividad';
			$result = selectfield($tabla,'userid',$userid);
			if ($result) {
				$query="UPDATE $tabla set  fecha='$fecha' where userid=$userid";
				if (! $mysqli->query($query)) {
					error_log("ERROR: No se pudo ejecutar $query. " . $mysqli->error,0);		
				}
		    } else {
				$query="INSERT INTO $tabla (userid,fecha) VALUES ('$userid','$fecha')";
				if (! $mysqli->query($query)) {
					error_log("ERROR: No se pudo ejecutar $query. " . $mysqli->error,0);		
				}
			}
			$mysqli->close();
		}
	}
	
	function obtener_fecha($userid) {
		if (isset($userid)) {
			include('./conectar.php');
			$query = "select fecha from inicio_actividad where userid=$userid";
			if ($result = $mysqli->query($query)) {
		    	$row = $result->fetch_array()[0];
			}
			$mysqli->close();
			return $row;
		}
	}
?>
