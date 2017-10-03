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

class Tablas {
    const USUARIOS = 'usuarios';
    const TAREAS = 'tarea';
    const PROYECTOS = 'proyecto';
    const ACTIVIDADES = 'actividad';
}

class Objeto {
    private $mysqli = false;
	public $tabla = TABLAS::TAREAS;
	public $regpp =  6;

	public function __construct($tabla,$regpp=6) {
		$this->tabla = $tabla;
		$this->regpp = $regpp;
        include('./conectar.php');
        $this->mysqli = $mysqli; 
	}
    
    public function __destruct() {
        $this->mysqli->close();
    }

	public function editar($id,$campos) {
		//include('./conectar.php');
		if($campos && isset($this->tabla)) {
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
			$query="UPDATE ".$this->tabla." set  $variables where id = $id";
			if (! $this->mysqli->query($query)) {
				error_log("ERROR: No se pudo ejecutar $query. " . $mysqli->error,0);
				echo "<p class=failure>¡Error al agregar el comentario al realizar query!</p>";
			} else {
				echo "<p class=succes>¡Se ha agregado con éxito!</p>";
			}
		} else {
			echo "<p class=failure>¡Error al procesar el formulario!</p>";
		}
		//$mysqli->close();
	}

	public function agregar($campos) {
		//include('./conectar.php');
		if(isset($campos) && isset($this->tabla)) {
			$i=0;
			$variables = '';
			$valores = '';
			foreach ($campos as $clave => $valor) {
				if ($i == 0) {
					$variables .= '('.$clave;
					$valores .= "('$valor'";
				} else {
					$variables .= ','.$clave;
					if ($clave == 'psw') {
						$valores .= ",password('$valor')";						
					} else {
						$valores .= ",'$valor'";
					}
				}
				$i++;
			}
			$variables .=')';
			$valores .=')';
			$query="INSERT INTO ".$this->tabla." $variables VALUES $valores";
			if (! $this->mysqli->query($query)) {
				error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
				echo "<p class=failure>¡Error al agregar el comentario al realizar query!</p>";
				$mysqli->close();
				return -1;
			} else {
				echo "<p class=succes>¡Se ha agregado con éxito!</p>";
				return 0;
			}
		} else {
			echo "<p class=failure>¡Error al agregar el comentario!</p>";
		}
		//$mysqli->close();
	}

	public function get($numpag,$campos,$where='') {
		$offset = ($numpag-1) * $this->regpp;
		//include('./conectar.php');
		$i = 0;
		$variables = '';
		$valores = '';
		foreach ($campos as $clave => $valor) {
			if ($i == 0) {
				$variables .= ' '.$clave;
			} else {
				$variables .= ','.$clave;
			}
			$i++;
		}
		if ($this->tabla == 'actividad') {
			$variables .= ',timestampdiff(SECOND,fecha_inicio,fecha_fin) as difdate';
		}

		$query = "SELECT" . " $variables FROM  ".$this->tabla;
		if ($where) {
			$query .= " where $where ";
		}
	
		if ($this->tabla == 'actividad') {
			$query .= " order by fecha_inicio desc ";
		} else if ($this->tabla != 'usuarios') {
			$query .= " order by fecha desc ";
		}

		$query .= " limit ".$offset.",".$this->regpp;

		if ($result = $this->mysqli->query($query)) {
	  		if ($result->num_rows > 0) {
				$objetos = array();
				while($fila = $result->fetch_object()) {
					$objetos[] = $fila;
				}
				$result->close();
				return $objetos;				
			} 
		} else {
			echo "<p class=failure>Error al ejecutar query : $query</p>";
	  		error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
		}
		//$mysqli->close();
	}

	public function mostrar($numpag,$campos,$where='',$textos=null) {
		$objetos = $this->get($numpag,$campos,$where);
		if (count($objetos) > 0) {
			echo "<tr>";
			if (!isset($textos)) {
				$textos = $this->getTexts();
			}
			foreach ($objetos[0] as $clave => $valor) {
				if (isset($textos[$clave])) {
					echo "<th>".$textos[$clave]."</th>";
				} else {
					echo "<th>$clave</th>";
				}
			}
			if ($this->tabla == "tarea") {
				echo "<th>Borrar</th>";
				echo "<th>Editar</th>";
			}
			echo "</tr>";
			$i=0;
			foreach ($objetos as $objeto) {
				echo "<tr bgcolor=" . (($i%2) ? "#552729" : "#774949") .">";
				foreach ($objeto as $clave => $valor) {
					if($this->tabla == 'tarea' && $clave == 'nombre') {
						echo "<td><a href='index.php?pag=actividad&tareaid=".$objeto->id."'>".$valor."</a></td>";
					} else if ($this->tabla == 'actividad' && $clave == 'difdate') {
						echo "<td>".gmdate("H:i:s",$valor)."</td>";
					} else {
						echo "<td>".$valor."</td>";
					}
				} 
				if($this->tabla == 'tarea') {
					echo "<td><a href='index.php?pag=tarea&tareaid=".$valor."&msg=del'>Borrar</a></td>";
					echo "<td><a href='index.php?pag=tarea&tareaid=".$valor."&msg=editar'>Editar</a></td>";
				}
				echo "</tr>\n";
				$i++;
			}
		} else {
			echo "<p class=failure>Error no se encontraron registros</p>";
		}
	}

	public function getTexts() {
		$fh = fopen('./textos/textos.txt','r');
		$textos = array();
		while ($linea = fgets($fh)) {
		  $array=explode("::",$linea);
		  if ($array[0] == $this->tabla) {
			$textos[$array[1]] = $array[2];
		  }
		}
		fclose($fh);
		if (count($textos) > 0) {
			return $textos;
		} else {
			return null;
		}
	}
		
}	

function svariables($table,$i1,$i2) {
	include('./conectar.php');
	$query = "select * from $table";
	$variable=[];
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
	$mysqli->close();
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
	$mysqli->close();
}

function selectfield($table,$campo,$id=NULL) {
	include('./conectar.php');
	$query = "select $campo from $table";
	if (isset($id)) {
		if ($table == "inicio_actividad") {
			$query.= " where userid=$id";		
		} else {
			$query.= " where id=$id";
		}
	}
	if ($result = $mysqli->query($query)) {
		$variable=$result->fetch_array()[0];
		$mysqli->close();
		return $variable;
	} else {
		echo "<p class=failure>Error al ejecutar query</p>";
		error_log("ERROR: Could not execute $query. " . $mysqli->error,0);
	}
	$mysqli->close();
}

?>
