<h1>Tarea</h1>
<?php
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
	$fechaArr = array();
	$fechaArrStr = "[";
	for ($i=0;$i<7;$i++) {
		$fechaArr[$i] = date("Y-m-d",time()-24*3600*$i);
		if ($i<6) {
			$fechaArrStr = $fechaArrStr."'".$fechaArr[$i]."'".","; 	
		} else {
			$fechaArrStr = $fechaArrStr."'".$fechaArr[$i]."'"."]"; 					
		}	
	}
?>
<table id="cabecera_actividad">
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

function agregaractividad($nombre,$texto,$userid,$tareaid,$status,$fecha_inicio,$fecha_fin) {
	/*$fecha_ini = obtener_fecha($userid);
	if (!isset($fecha_inicio)) {
		return -1;
	}*/
	/*$campos['fecha_inicio']=$fecha_ini;
	$campos['fecha_fin']=date("Y-m-d H:i:s");*/
	$campos['fecha_inicio']=$fecha_inicio;
	$campos['fecha_fin']=$fecha_fin;
	$campos['titulo'] = $nombre;
	$texto = trim($texto);
	$texto = nl2br($texto);
	$campos['descripcion'] = $texto;
	$campos['userid'] = $userid;
	$campos['tareaid'] = $tareaid;
	$tabla = 'actividad';
	if (agregar($campos,$tabla)==-1) {
		return -1;
	}
	if ($status == 0) {
		modificarstatus($tareaid,1);
	}
	inicio_actividad($userid);
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
	$fecha_inicio = sprintf("%s %s:%s:%s",$fechaArr[$fechaini],$horaini,$minini,$secini);
	$fecha_fin = sprintf("%s %s:%s:%s",$fechaArr[$fechafin],$horafin,$minfin,$secfin);
	echo $fecha_inicio." ".$fecha_fin;
	if ($nombre && $texto && $useridl && $tareaid && checkst($status,$error)) {
		if(agregaractividad($nombre,$texto,$useridl,$tareaid,$status,$fecha_inicio,$fecha_fin) == -1) {
			$error['fecha_inicio']=1;
			echo "<p class=failure>Error al agregar actividad campos incorrectos</p>";
		} 	
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
		<tr><td>Inicio : </td><td>
			<?php
				$fecha_inicio=selectfield('inicio_actividad','fecha',$useridl);
				if (isset($fecha_inicio)) {
					/*$ano = substr($fecha_inicio,0,4);
					$mes = substr($fecha_inicio,5,2);
					$dia = substr($fecha_inicio,8,2);*/
					$fecha = substr($fecha_inicio,0,10);
					$hora = substr($fecha_inicio,11,2);
					$min = substr($fecha_inicio,14,2);
					$sec = substr($fecha_inicio,17,2);
				} else {
					
					/*$ano = date("Y");
					$mes = date("m");
					$dia = date("d");*/
					$fecha = date("Y-m-d");
					$hora = date("H");
					$min = date("i");
					$sec = date("s");
				}
				fechahora("ini",$fechaArrStr,$fechaini ? $fechaini : $fecha,$horaini ? $horaini : $hora,$minini ? $minini : $min,$secini ? $secini : $sec);
				//horas("ini",$horaini ? $horaini : $hora,$minini ? $minini : $min,$secini ? $secini : $sec);
				/*
				$anoini = $ano;
				$mesini = $mes;
				$diaini = $dia;*/
				//echo "<input name='anoini' type='hidden' value=$anoini>";
				//echo "<input name='mesini' type='hidden' value=$mesini>";
				//echo "<input name='diaini' type='hidden' value=$diaini>";				
			?>
		</td></tr>
		<tr><td>Fin : </td><td>
			
			<?php 
			$curh = date("H");
			$curm = date("i");
			$curs = date("s");
			$curf = date("Y-m-d");
			fechahora("fin",$fechaArrStr,$fechafin ? $fechafin : $fecha,$horafin ? $horafin : $hora,$minfin ? $minfin : $min,$secfin ? $secfin : $sec)
			/*horas("fin",$horafin ? $horafin : $curh,
						$minfin ? $minfin : $curm,$secfin ? $secfin : $curs); 
			$anofin = date("Y");
			$mesfin = date("m");
			$diafin = date("d");
			echo "<input name='anofin' type='hidden' value=$anofin>";
			echo "<input name='mesfin' type='hidden' value=$mesfin>";
			echo "<input name='diafin' type='hidden' value=$diafin>";*/				
			?>
			
		</td></tr>
		<!--<tr><td>Fecha Inicio</td><td>
			<?php /*
				$fecha_inicio=selectfield('inicio_actividad','fecha',$useridl);
				if (isset($fecha_inicio)) {
					$ano = substr($fecha_inicio,0,4);
					$mes = substr($fecha_inicio,5,2);
					$dia = substr($fecha_inicio,8,2);
					$hora = substr($fecha_inicio,11,2);
					$min = substr($fecha_inicio,14,2);
					$sec = substr($fecha_inicio,17,2);
				} else {
					$ano = date("Y");
					$mes = date("m");
					$dia = date("d");
					$hora = date("H");
					$min = date("i");
					$sec = date("s");
				}
						
			fecha("ini",$anoini ? $anoini : $ano,$mesini ? $mesini : $mes,
						$diaini ? $diaini : $dia,$horaini ? $horaini : $hora,
						$minini ? $minini : $min,$secini ? $secini : $sec); 
			errorform($error,'fecha_inicio');*/
			?>
			</td>
		</tr>
		<tr><td>Fecha Fin:</td><td>
			<?php /*fecha("fin",$anofin ? $anofin : date("Y"),$mesfin ? $mesfin : date("m"),
						$diafin ? $diafin : date("d"),$horafin ? $horafin : date("H"),
						$minfin ? $minfin : date("i"),$secfin ? $secfin : date("s")); 
			*/?>
		</td></tr>-->
		<tr><td><input type="submit" value="Enviar"></td><td>Finalizar Tarea<input type="checkbox" name="ftarea"></td></tr>
	</table>
</form>
<script type="text/javascript">	
	fechahora(<?php echo "\"ini\",".$fechaArrStr.",".($fechaini ? "\"".$fechaini."\"" : "\"".$fecha."\"").",".($horaini ? $horaini : $hora).",".($minini ? $minini : $min).",".($secini ? $secini : $sec)
	.",\"".$curf."\"".",".$curh.",".$curm.",".$curs; ?>);
	fechahora(<?php echo "\"fin\",".$fechaArrStr.",".($fechafin ? "\"".$fechafin."\"" : "\"".$fecha."\"").",".($horafin ? $horafin : $hora).",".($minfin ? $minfin : $min).",".($secfin ? $secfin : $sec)
	.",\"".$curf."\"".",".$curh.",".$curm.",".$curs; ?>);
	

/*	horas(<?php echo "\"ini\",".($horaini ? $horaini : $hora).",".($minini ? $minini : $min).",".($secini ? $secini : $sec)
	.",$curh,$curm,$curs"; ?>);
	horas(<?php echo "\"fin\",".($horafin ? $horafin : $curh).",".($minfin ? $minfin : $curm).",".($secfin ? $secfin : $curs)
	.",$curh,$curm,$curs"; ?>);*/

</script>


