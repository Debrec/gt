<?php
function errorform($error,$name) {
	if (isset($error[$name])) {
		echo "<span name='error_nombre' class='error'>Error campo $name vacio</span>";
	}
}

function modificarstatus($tareaid,$status) {
		include('./conectar.php');
		$query = "update tarea set status = $status where id=$tareaid";
		if (!$mysqli->query($query)) {
			error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
			echo "<p class=failure>¡Error al modificar status!</p>";
		}
		$mysqli->close();
}

function borrar($tabla,$id) {
	include('./conectar.php');
	if (isset($id)) {
		$query = "delete from $tabla where id=$id";
		if (! $mysqli->query($query)) {
			error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
			echo "<p class=failure>¡Error al eliminar fila!</p>";
		} else {
			echo "<p class=succes>¡Se ha eliminado la fila con exito!</p>";
		}
		$mysqli->close();
	} else {
		echo "<p class=failure>Error falta id al borrar</p>";
	}
}

function editar($id,$campos,$tabla) {
	include('./conectar.php');
	if($campos && isset($tabla)) {
		$i=0;
		$variables = '';
		foreach ($campos as $clave => $valor) {
			if ($i == 0) {
				$variables .= "$clave = '$valor'";
			} else {
				$variables .= ",$clave = '$valor'";
			}
			$i++;
		}
		$query="UPDATE $tabla set  $variables where id = $id";
		if (! $mysqli->query($query)) {
			error_log("ERROR: No se pudo ejecutar $query. " . $mysqli->error,0);
			echo "<p class=failure>¡Error al agregar el comentario al realizar query!</p>";
		} else {
			echo "<p class=succes>¡Se ha agregado con éxito!</p>";
		}
	} else {
		echo "<p class=failure>¡Error al procesar el formulario!</p>";
	}
	$mysqli->close();
}

function agregar($campos,$tabla) {
	include('./conectar.php');
	if(isset($campos) && isset($tabla)) {
		$i=0;
		$variables = '';
		$valores = '';
		foreach ($campos as $clave => $valor) {
			if ($i == 0) {
				$variables .= '('.$clave;
				$valores .= "('$valor'";
			} else {
				$variables .= ','.$clave;
				$valores .= ",'$valor'";
			}
			$i++;
		}
		$variables .=')';
		$valores .=')';
		$query="INSERT INTO $tabla $variables VALUES $valores";
		if (! $mysqli->query($query)) {
			error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
			echo "<p class=failure>¡Error al agregar el comentario al realizar query!</p>";
		} else {
			echo "<p class=succes>¡Se ha agregado con éxito!</p>";
		}
	} else {
		echo "<p class=failure>¡Error al agregar el comentario!</p>";
	}
	$mysqli->close();
}

function mostrar($numpag,$regpp,$campos,$tabla,$where='') {
	$offset = ($numpag-1) * $regpp;
	include('./conectar.php');
	$i = 0;
	$variables = '';
	$valores = '';
	foreach ($campos as $clave => $valor) {
		if ($i == 0) {
			$variables .= ' '.$clave;
			$valores .= "('$valor'";
		} else {
			$variables .= ','.$clave;
			$valores .= ",'$valor'";
		}
		$i++;
	}
	if ($tabla == 'actividad') {
		$variables .= ',timestampdiff(SECOND,fecha_inicio,fecha_fin)';
	}

	$query = "SELECT" . " $variables FROM  $tabla";
	if ($where) {
		$query .= " where $where ";
	}
	
	if ($tabla == 'actividad') {
		$query .= " order by fecha_inicio desc ";
	} else if ($tabla != 'usuarios') {
		$query .= " order by fecha desc ";
	}

	$query .= " limit ".$offset.","."$regpp";

	if ($result = $mysqli->query($query)) {
	  if ($result->num_rows > 0) {
			echo "<tr>";
			foreach ($campos as $clave => $valor) {
				echo "<th>$clave</th>";
			}
			if ($tabla == "actividad") {
				echo "<th>Duración</th>";
			} else if ($tabla == "tarea") {
				echo "<th>Borrar</th>";
				echo "<th>Editar</th>";
			}
			echo "</tr>";
			$i = 0;
	    while(($row = $result->fetch_array())) {
				echo "<tr bgcolor=" . (($i%2) ? "#552729" : "#774949") .">";
				for ($j=0;$j<count($campos);$j++) {
					if($tabla == 'tarea' && $j == 1) {
						echo "<td><a href='index.php?pag=actividad&tareaid=".$row[0]."'>".$row[$j]."</a></td>";
					} else {
						echo "<td>".$row[$j]."</td>";
					}
				} 
				if ($tabla == 'actividad') {
					echo "<td>".gmdate("H:i:s",$row[count($campos)])."</td>";				
				}
				if($tabla == 'tarea') {
					echo "<td><a href='index.php?pag=tarea&tareaid=".$row[0]."&msg=del'>Borrar</a></td>";
					echo "<td><a href='index.php?pag=tarea&tareaid=".$row[0]."&msg=editar'>Editar</a></td>";
				}
				echo "</tr>\n";
				$i++;
			}
	    $result->close();
	  } else {
	    echo "<p class=failure>Error no se encontraron registros</p>";
	  }
	} else {
		echo "<p class=failure>Error al ejecutar query : $query</p>";
	  error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
	}
}

function svariables($table,$i1,$i2) {
	include('./conectar.php');
	$query = "select * from $table";
	if ($result = $mysqli->query($query)) {
		while(($row = $result->fetch_array())) {
			$variable[$row[$i1]]=$row[$i2];
		}
		$mysqli->close();
		return $variable;
	} else {
		echo "<p class=failure>Error al ejecutar query : $query</p>";
	  error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
	}
}

function sobject($table,$id) {
	include('./conectar.php');
	$query = "select * from $table where id=$id";
	if ($result = $mysqli->query($query)) {
		$variable=$result->fetch_object();
		$mysqli->close();
		return $variable;
	} else {
		echo "<p class=failure>Error al ejecutar query</p>";
	  error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
	}
}

function selectfield($table,$campo,$id) {
	include('./conectar.php');
	$query = "select $campo from $table where id=$id";
	if ($result = $mysqli->query($query)) {
		$variable=$result->fetch_array()[0];
		$mysqli->close();
		return $variable;
	} else {
		echo "<p class=failure>Error al ejecutar query</p>";
	  error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
	}
}

?>
