<?php
$error = array();

function borrartarea($tareaid) {
	borrar('tarea',$tareaid);
}

function editartarea($tareaid,$nombre,$texto,$userid,$proyectid) {
	$tarea = sobject('tarea',$tareaid);
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
	$tabla = 'tarea';
	editar($tareaid,$campos,$tabla);
}

function agregartarea($nombre,$texto,$userid,$proyectid) {
	$campos['fecha']=date("Y-m-d H:i:s");
	$campos['nombre'] = $nombre;
	$texto = trim($texto);
	$texto = nl2br($texto);
	$campos['descripcion'] = $texto;
	$campos['userid'] = $userid;
	$campos['proyectid'] = $proyectid;
	$tabla = 'tarea';
	agregar($campos,$tabla);
}

function mostrartarea($numpag,$regpp,$status) {
	$campos['id'] = 0;
	$campos['nombre'] = 0;
	$campos['descripcion'] = 0;
	$tabla = 'tarea';
	if (isset($status) && $status != 4) {
		$where = " status = $status ";
	}

	mostrar($numpag,$regpp,$campos,$tabla,isset($where) ? $where : null);
}

if ($msg == 'add') {
		if ($nombre && $texto && $userid && $proyectid) {
			agregartarea($nombre,$texto,$userid,$proyectid);
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
	borrartarea($tareaid);
} else if ($msg == 'edit') {
	if ($tareaid && $nombre && $texto && $userid && $proyectid) {
		editartarea($tareaid,$nombre,$texto,$userid,$proyectid);
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
	$tarea = sobject('tarea',$tareaid);
	$nombre = $tarea->nombre;
	$texto = $tarea->descripcion;
	$userid = $tarea->userid;
	$proyectid = $tarea->proyectid;
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
				$vars[0] = "Tarea Sin Comenzar";
				$vars[1] = "Tarea Comenzada";
				$vars[2] = "Tarea Finalizada";
				$vars[3] = "Pedido de definiciones";
				$vars[4] = "Todas las tareas";
				if (!isset($status)) {
					$status = 4;
				}
				foreach ($vars as $clave => $valor) {
					echo "<option value='$clave'".(($status == $clave) ? 'selected' : '').">$valor</option>\n";
				}
				?>
			</select>
			<!--<input type="submit" value="Enviar">-->
		</td></tr>
	</table>
</form>
<table border=1 align="center">
<?php mostrartarea(isset($numpag) ? $numpag : 1,6,$status); ?>
</table>
<br>
<?php
	include('./funciones.php');
	paginas(isset($numpag) ? $numpag : 1,6,'tarea','tarea',0,$status);
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
						$usuarios=svariables('usuarios',0,4);
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
						$proyectos=svariables('proyecto',0,2);
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
