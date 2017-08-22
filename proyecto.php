<?php
$error = array();
function agregarproyecto($nombre,$comentario) {
	$campos['fecha']=date("Y-m-d H:i:s");
	$campos['nombre'] = $nombre;
	$comentario = trim($comentario);
	$comentario = nl2br($comentario);
	$campos['comentario'] = $comentario;
	$tabla = 'proyecto';
	agregar($campos,$tabla);
}

function mostrarproyecto($numpag,$regpp) {
	$campos['fecha'] = 0;
	$campos['nombre'] = 0;
	$campos['comentario'] = 0;
	$tabla = 'proyecto';
	mostrar($numpag,$regpp,$campos,$tabla);
}

if ($msg == 'add') {
		if ($nombre && $comentario) {
			agregarproyecto($nombre,$comentario);
		} else {
			if (!$nombre) {
				$error['nombre'] = 1;
			}
			if (!$texto) {
				$error['comentario'] = 1;
			}
			echo "<p class=failure>Error al agregar proyecto campos incompletos</p>";
		}
}
?>
<h1 class="subt">Proyectos</h1>
<p>Proyectos</p>
<table border=1 align="center">
<?php mostrarproyecto(isset($numpag) ? $numpag : 1,6); ?>
</table>
<br>
<?php
	include('./funciones.php');
	paginas(isset($numpag) ? $numpag : 1,6,'proyecto','proyecto');
?>
<br>
<p>Agregar proyecto</p>

<form name="fcont" method="post" action="./index.php?pag=proyecto&msg=add" >
	<table width="100%">
		<tr><td>Nombre</td><td><input name="nombre" id="nombre" type="text" size="50" maxlength="100" value="<?php echo $nombre ?>"><?php errorform($error,'nombre'); ?></td></tr>
		<tr><td>Texto</td><td><textarea name="comentario" title="comentario" maxlength="1000" cols="50" rows="10" label="Comentario"><?php echo $comentario ?></textarea><?php errorform($error,'comentario'); ?></td></tr>
		<tr><td colspan="2"><input type="submit" value="Enviar"></td></tr>
	</table>
</form>
