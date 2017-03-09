<?php
	include('inicio_actividad.php');
	if ($msg == 'reset') {
		inicio_actividad();
	}	

?>
<ul>
	<li><a href="index.php?pag=usuarios">Usuarios</a></li>
	<li><a href="index.php?pag=proyecto">Proyecto</a></li>
	<li><a href="index.php?pag=tarea">Tarea</a></li>
	<li><a href="logout.php">Logout</a></li>
	<li><a href="index.php?msg=reset">Reset Time</a></li>
	<li><span>Fecha Inicio: <?php echo obtener_fecha(); ?></span></li>
</ul>
