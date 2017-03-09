<h1>Tarea</h1>
<?php
	include("./msql.php");
	$error = array();
	$email = selectfield('usuarios','email',$useridl);
	$tarea = sobject('tarea',$tareaid);
	$proyecto = sobject('proyecto',$tarea->proyectid);
	$usuario = sobject('usuarios',$tarea->userid);
	$status = $tarea->status;
	if (isset($ftarea)) {
		modificarstatus($tareaid,2);
		$status = 2;
	}
	if (isset($reabrir)) {
		modificarstatus($tareaid,1);
		$status = 1;
	}

	function textst($status) {
		switch ($status) {
			case 0:
				echo "Tarea no Comenzada";
				break;
			case 1:
				echo "Tarea Comenzada";
				break;
			case 2:
				echo "Tarea Finalizada";
				break;
			case 3:
				echo "Esperando Definiciones";
				break;
			default;
				echo "Status desconocido";
				break;
		}
	}
?>
<table>
	<tr><th>Usuario : </th><td><?php echo $usuario->email ?></td></tr>
	<tr><th>Proyecto : </th><td><?php echo $proyecto->nombre ?></td></tr>
	<tr><th>Tarea : </th><td><?php echo $tarea->nombre ?></td></tr>
	<tr><th>Descripción :</th><td><?php echo $tarea->descripcion ?></td></tr>
	<tr><th>Status : </th><td><?php textst($status)  ?></td>
</table>
<?php if ($status == 2) { ?>
	<form name="changest" method="post" action="./index.php?pag=actividad&tareaid=<?php echo $tareaid ?>" >
	<table width="100%">
		<tr><td>
			<input type='submit' value='Reabrir'>
		</td>
		<td><input name='reabrir' type='hidden' value='1'>
		</td></tr>
	</table>
	</form>
<?php } ?>
<?php

function agregaractividad($nombre,$texto,$userid,$tareaid,$status) {
	$campos['fecha_inicio']=obtener_fecha();
	$campos['fecha_fin']=date("Y-m-d H:i:s");
	$campos['titulo'] = $nombre;
	$texto = trim($texto);
	$texto = nl2br($texto);
	$campos['descripcion'] = $texto;
	$campos['userid'] = $userid;
	$campos['tareaid'] = $tareaid;
	$tabla = 'actividad';
	agregar($campos,$tabla);
	if ($status == 0) {
		modificarstatus($tareaid,1);
	}
	inicio_actividad();
}

function mostraractividad($numpag,$regpp,$tareaid) {
	$campos['id'] = 0;
	$campos['fecha_inicio'] = 0;
	$campos['fecha_fin']=0;
	$campos['titulo'] = 0;
	$campos['descripcion'] = 0;
	$tabla = 'actividad';
	mostrar($numpag,$regpp,$campos,$tabla," tareaid = $tareaid ");
}

function checkst($status,$error) {
	if (isset($status) && (($status == 0) || ($status == 1) || ($status == 2) || ($status == 3))) {
		return 1;
	} else {
		$error['status'] = 1;
		return 0;
	}
}

if ($msg == 'add') {
	if ($nombre && $texto && $useridl && $tareaid && checkst($status,$error)) {
		agregaractividad($nombre,$texto,$useridl,$tareaid,$status);
	} else {
		if (!$nombre) {
			$error['nombre'] = 1;
		}
		if (!$texto) {
			$error['descripcion'] = 1;
		}
		echo "<p class=failure>Error al agregar actividad campos incompletos</p>";
	}
}
include('./scripts.php');
?>

<h1 class="subt">Actividades</h1>
<table border=1 align="center">
<?php mostraractividad(isset($numpag) ? $numpag : 1,6,$tareaid); ?>
</table>
<br>
<?php
	include('./funciones.php');
	paginas(isset($numpag) ? $numpag : 1,6,'actividad','actividad',$tareaid);
?>
<br>

<p>Agregar Actividad</p>
<?php
echo '<form name="fcont" method="post"
 action="./index.php?pag=actividad&msg=add&tareaid='.$tareaid.'">';
?>
	<table width="100%">
		<tr><td>Nombre</td><td><input name="nombre" id="nombre" type="text" size="50" maxlength="100" value="<?php echo $nombre ?>"><?php errorform($error,'nombre'); ?></td></tr>
		<tr><td>Descripcion</td><td><textarea name="descripcion" title="descripcion" maxlength="1000" cols="50" rows="10" label="Descripcion"><?php echo $texto ?></textarea><?php errorform($error,'descripcion'); ?></td></tr>
		<tr><td>Fecha Fin:</td><td>
			<span>Año : 
				<select name="anofin">	
					<?php 
						$cur=date("Y");
						if (date("m")==1) {
							$prev=$cur-1;
							echo "<option value=$prev>$prev</option>";
						}
						echo "<option selected value=$cur>$cur</option>";
					?>
				</select>
			</span> 
			<span> Mes : 
				<select name="mesfin">
					<?php 
						$cur=date("m");
						if (date("d")==1) {
							$prev=$cur-1;
							echo "<option value=$prev>$prev</option>";
						}
						echo "<option value=$cur>$cur</option>";
					?>					
				</select>	
			</span>
			<span> Día : 
				<select name="diafin">
					<?php 
						$cur=date("d");
						$prev=$cur-1;
						echo "<option value=$prev>$prev</option>";
						echo "<option selected value=$cur>$cur</option>";
					?>					
				</select>	
			</span>
			<span> Hora : 
				<select name="horafin">
					<?php 
						$cur=date("H");
						for ($i=0;$i<=$cur;$i++) {
							echo "<option value=$i>$i</option>";
						}
					?>					
				</select>	
			</span>
		</td></tr>		
		<tr><td><input type="submit" value="Enviar"></td><td>Finalizar Tarea<input type="checkbox" name="ftarea"></td></tr>
	</table>
</form>
