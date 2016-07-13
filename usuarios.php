<?php

include("./msql.php");
function agregarusuario($nombre,$apellido,$email) {
	$campos['nombres'] = $nombre;
	$campos['apellido'] = $apellido;
	$campos['email'] = $email;
	$tabla = 'usuarios';
	agregar($campos,$tabla);
}

function mostrarusuarios($numpag,$regpp) {
	$campos['nombres'] = 0;
	$campos['apellido'] = 0;
	$campos['email'] = 0;
	$tabla = 'usuarios';
	mostrar($numpag,$regpp,$campos,$tabla);
}

if ($msg == 'add') {
	if (isset($nombre) && isset($apellido) && isset($email)) {
		agregarusuario($nombres,$apellido,$email);
	} else {
		echo "Error al agregar usuario, campos incompletos";
	}
}
include('./scripts.php');
?>

<h1 class="subt">Usuarios</h1>
<p>Usuarios</p>
<table border=1 align="center">
<?php mostrarusuarios(isset($numpag) ? $numpag : 1,6); ?>
</table>
<br>
<?php
	include('./funciones.php');
	paginas(isset($numpag) ? $numpag : 1,6,'usuarios','usuarios');
?>
<br>
<p>Agregar usuario</p>

<form name="fcont" method="post" action="./index.php?pag=usuarios&msg=add" >
	<table width="100%">
		<tr><td>Nombre</td><td><input name="nombres" id="nombres" type="text" size="30" maxlength="30" value="<?php echo $nombres ?>"></td></tr>
		<tr><td>Apellido</td><td><input name="apellido" id="apellido" type="text" size="30" maxlength="30" value="<?php echo $apellido ?>"></td></tr>
		<tr><td>e-mail</td><td><input OnBlur="checkmail();" name="email" id="email" type="text" size="40" maxlength="40" value="<?php echo $email ?>"></td></tr>
		<tr><td colspan="2"><input type="submit" value="Enviar"></td></tr>
	</table>
</form>