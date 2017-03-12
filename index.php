<html>
<head>
	<title>
	<?php
		function asignar($var,$index) {
			if (isset($var[$index])) {
				return $var[$index];
			} else {
				return NULL;
			}
		}
		$pag = asignar($_GET,"pag");
		$msg = asignar($_GET,"msg");
		$numpag = asignar($_GET,"numpag");
		$tareaid = asignar($_GET,"tareaid");
		if (!isset($pag)) {
			echo "Gestor de tareas";
		} else if ($pag == 'usuarios') {
			echo 'Usuarios';
		} else if ($pag == 'proyecto') {
			echo 'Proyecto';
		} else if ($pag == 'tarea') {
			echo 'Tareas';
		} else {
			echo "Gestor de tareas";
		}
	?>
	</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="es">
<meta content="gestor de tareas" name="description">
<meta content="Spanish" name="language">
<link type="text/css" rel="stylesheet" href="import.css">
</head>
<body>
<?php
	session_start();

	function verificar_login($user,$password,&$result) {
			include('./conectar.php');
			$sql = "SELECT * FROM usuarios WHERE email = '$user' and psw =password('$password')";
    	$rec = $mysqli->query($sql);
    	$count = 0;

    	while($row = $rec->fetch_object()) {
        $count++;
        $result = $row;
    	}

    	if($count == 1) {
        return 1;
    	} else {
        return 0;
    	}
	}

	if(!isset($_SESSION['userid'])) {
		if(isset($_POST['login'])) {
			if(verificar_login($_POST['user'],$_POST['password'],$result) == 1) {
				$_SESSION['userid'] = $result->id;
				header("location:index.php");
			} else {
				echo '<p class="failure">Su usuario es incorrecto, intente nuevamente.</p>';
			}
		}
?>

<form name="fcont" action="" method="post" class="login">
    <div><label>Username</label><input name="user" type="text" ></div>
    <div><label>Password</label><input name="password" type="password"></div>
    <div><input name="login" type="submit" value="login"></div>
</form>
<?php
	} else {
?>
<div class=sup>
	<?php include("./superior.php"); ?>
</div>
<div class=lateral>
	<?php 
		$useridl = asignar($_SESSION,'userid');
		include("./msql.php");
		include('./lateral.php');	
	?>
</div>
<div class=blanco>
<?php
		$nombre = asignar($_POST,'nombre');
		$nombres = asignar($_POST,'nombres');
		$apellido = asignar($_POST,'apellido');
		$email = asignar($_POST,'email');
		$comentario = asignar($_POST,'comentario');
		$texto = asignar($_POST,'descripcion');
		$userid = asignar($_POST,'userid');
		$proyectid = asignar($_POST,'proyectid');
		$status = asignar($_POST,'status');
		$ftarea = asignar($_POST,'ftarea');
		$reabrir = asignar($_POST,'reabrir');
		if (!isset($pag)) {
			include('./tareas.php');
		} else if ($pag == 'usuarios') {
			include('./usuarios.php');
		} else if ($pag == 'proyecto') {
			include('./proyecto.php');
		} else if ($pag == 'tarea') {
			include('./tareas.php');
		} else if ($pag=='actividad') {
			$anofin = asignar($_POST,'anofin');
			$mesfin = asignar($_POST,'mesfin');
			$diafin = asignar($_POST,'diafin');
			$horafin = asignar($_POST,'horafin');
			$minfin = asignar($_POST,'minfin');
			$secfin = asignar($_POST,'secfin');
			
			
			$anoini = asignar($_POST,'anoini');
			$mesini = asignar($_POST,'mesini');
			$diaini = asignar($_POST,'diaini');
			$horaini = asignar($_POST,'horaini');
			$minini = asignar($_POST,'minini');
			$secini = asignar($_POST,'secini');

			include('./actividad.php');
		} else {
			echo "<h1>seleccionar una opción</h1>";
		}
	}
?>
</div>
</body>
</html>
