<?php
	if ($msg == 'reset') {
        $inicio_actividad->inicio_actividad($useridl);
	}	

?>
<ul>
	<li><a href="index.php?pag=usuarios">Usuarios</a></li>
	<li><a href="index.php?pag=proyecto">Proyectos</a></li>
	<li><a href="index.php?pag=tarea">Tareas</a></li>
	<li><a href="logout.php">Logout</a></li>
	<li><a href="index.php?pag=tarea&msg=reset">Reset Time</a></li>
	<li><span id="fi">Fecha Inicio: <?php echo $inicio_actividad->obtener_fecha($useridl); ?></span></li>
</ul>
