<?php
$error = array();
class Tareas extends Objeto {

    public $userid = 0;

	public function __construct($useridl) {
        $this->userid = $useridl;
		parent::__construct(TABLAS::TAREAS);
	}
	
	public function borrar($tareaid) {
		modificarstatus($tareaid,5);
	}

	public function editar($tareaid,$nombre,$texto=null,$userid=null,$proyectid=null) {
		$tarea = sobject($this->tabla,$tareaid);
		if (isset($nombre) && ($nombre != $tarea->nombre)) {
			$campos['nombre'] = $nombre;
		}
		$texto = trim($texto);
		$texto = nl2br($texto);
		if (isset($texto) && ($texto != $tarea->descripcion)) {
			$campos['descripcion'] = $texto;
		}
		if (isset($userid) && ($userid != $tarea->userid)) {
			$campos['userid'] = $userid;
		}
		if (isset($proyectid) && ($proyectid != $tarea->proyectid)) {
			$campos['proyectid'] = $proyectid;
		}
		parent::editar($tareaid,$campos);
	}

	function agregar($nombre,$texto=null,$userid=null,$proyectid=null) {
		$campos['fecha']=date("Y-m-d H:i:s");
		$campos['nombre'] = $nombre;
		$texto = trim($texto);
		$texto = nl2br($texto);
		$campos['descripcion'] = $texto;
		$campos['userid'] = $userid;
		$campos['proyectid'] = $proyectid;
		parent::agregar($campos);
	}

	function mostrar($numpag,$status,$where=null,$textos=null) {
		$campos['id'] = 0;
		$campos['nombre'] = 0;
		$campos['descripcion'] = 0;
		if (isset($status) && $status != 4) {
			$where = " status = $status ";
		} else {
			$where = " status != 5 ";
		}
        
        if (isset($where)) {
        	$where .= "and userid = ".$this->userid." ";
        } else {
            $where = "and userid = ".$this->userid." ";
        }

		parent::mostrar($numpag,$campos,isset($where) ? $where : null);
	}
}

$tareas= new Tareas($useridl);

if ($msg == 'add') {
		if ($nombre && $texto && $userid && $proyectid) {
			$tareas->agregar($nombre,$texto,$userid,$proyectid);
		} else {
			if (!$nombre) {
				$error['nombre']=1;
			}
			if (!$texto) {
				$error['descripcion']=1;
			}
			if (!$userid) {
				$error['userid'] = 1;
			}
			if (!$proyectid) {
				$error['proyectid'] = 1;
			}
			echo "Error : Campos vacios o incompletos";
		}
} else if ($msg == 'del') {
	$tareas->borrar($tareaid);
} else if ($msg == 'edit') {
	if ($tareaid && $nombre && $texto && $userid && $proyectid) {
		$tareas->editar($tareaid,$nombre,$texto,$userid,$proyectid);
	} else {
		if (!$nombre) {
			$error['nombre']=1;
		}
		if (!$texto) {
			$error['descripcion']=1;
		}
		if (!$userid) {
			$error['userid'] = 1;
		}
		if (!$proyectid) {
			$error['proyectid'] = 1;
		}
	}
}

if ($msg == 'editar') {
	$tareao = sobject($tareas->tabla,$tareaid);
	$nombre = $tareao->nombre;
	$texto = $tareao->descripcion;
	$userid = $tareao->userid;
	$proyectid = $tareao->proyectid;
}
include('./scripts.php');
?>

<h1 class="subt">Tareas</h1>
<p>Tareas</p>
<form id='statusf' name="statusf" method="post" action="./index.php?pag=tarea">
	<table width="100%">
		<tr><td>
			<select name="status" onchange="this.form.submit()">
				<?php
				$vars = array();
				$vars[0] = "Tareas Sin Comenzar";
				$vars[1] = "Tareas Comenzadas";
				$vars[2] = "Tareas Finalizadas";
				$vars[4] = "Todas las tareas";
				if (!isset($status)) {
					$status = 4;
				}
				foreach ($vars as $clave => $valor) {
					echo "<option value='$clave'".(($status == $clave) ? 'selected' : '').">$valor</option>\n";
				}
				?>
			</select>
		</td></tr>
	</table>
</form>
<table border=1 align="center">
<?php $tareas->mostrar(isset($numpag) ? $numpag : 1,$status); ?>
</table>
<br>
<?php
	include('./funciones.php');
	$pagina = new Paginas($tareas->tabla,$tareas->regpp);
	$pagina->paginas(isset($numpag) ? $numpag : 1,0,$status);
?>
<br>
<?php
	if ($msg == 'editar') {
		$titulo = 'Editar Tarea';
		$msgf = '&msg=edit&tareaid='.$tareaid;
	} else {
		$titulo = 'Agregar Tarea';
		$msgf = '&msg=add';
	}
?>
<p><?php echo $titulo ?></p>

<form name="fcont" method="post" action="./index.php?pag=tarea<?php echo $msgf  ?>"	>
	<table width="100%">
		<tr><td>Nombre</td><td><input name="nombre" id="nombre" type="text" size="50" maxlength="100" value="<?php echo $nombre ?>">
			<?php errorform($error,'nombre'); ?>
		</td></tr>
		<tr><td>Descripcion</td><td><textarea name="descripcion" title="descripcion" maxlength="1000" cols="50" rows="10" label="Descripcion"><?php echo $texto ?></textarea>
			<?php errorform($error,'descripcion'); ?>
		</td></tr>
		<tr><td>Usuarios</td>
			<td>
				<select name="userid">
					<?php
						$usuarios=svariables(TABLAS::USUARIOS,0,4);
						$userid = isset($userid)  ? $userid : $useridl;
						foreach ($usuarios as $clave => $valor) {
						echo "<option value='$clave'".(($userid == $clave) ? 'selected' : '').">$valor</option>\n";
					}
					?>
			</select>
		</td></tr>
		<tr><td>Proyecto</td>
			<td>
				<select name="proyectid">
					<?php
						$proyectos=svariables(TABLAS::PROYECTOS,0,2);
						$proyectid = isset($proyectid)  ? $proyectid : 1;
						foreach ($proyectos as $clave => $valor) {
						echo "<option value='$clave'".(($proyectid == $clave) ? 'selected' : '').">$valor</option>\n";
					}
					?>
			</select>
		</td></tr>
		<tr><td colspan="2"><input type="submit" value="Enviar"></td></tr>
	</table>
</form>
