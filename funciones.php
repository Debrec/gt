<?php
function contarceldas($tabla,$tareaid=0,$status=4) {
	include('./conectar.php');
	$querymax = "SELECT COUNT(*) FROM $tabla";
	if ($tareaid != 0) {
		$querymax .= " where tareaid = $tareaid";
	} else if ($status != 4) {
		$querymax .= " where status = $status ";
	}
	$imax = $mysqli->query($querymax);
	$mysqli->close();
	return $imax->fetch_array()[0];
}

function paginas($numpag,$regpp,$pag,$tabla,$tareaid=0,$status=4) {
	if ($tareaid != 0 ) {
		$tarea = "&tareaid=$tareaid";
	} else {
		$tarea = '';
	}

	$imax = contarceldas($tabla,$tareaid,$status);
	$maxpag = (int) ceil($imax/$regpp);
	$numpag = (int) (isset($numpag) ? $numpag : 1);
	echo '<div class="center"><p align="center">';

	if ($maxpag == 0) {

	} else if ($maxpag == (int)1 ) {
		echo "<a href=\"index.php?pag=$pag&numpag=1".$tarea."\">1</a>&nbsp;";
	} else if ($maxpag == (int)2) {
		echo "<a href=\"index.php?pag=$pag&numpag=1".$tarea."\">1</a>&nbsp;";
		echo "<a href=\"index.php?pag=$pag&numpag=2".$tarea."\">2</a>&nbsp;";
	} else if ($maxpag == $numpag) {
		echo '<a href="index.php?pag='.$pag.'&numpag='.($maxpag-1).$tarea.'">'.($maxpag-1).'</a>&nbsp;';
		echo '<a href="index.php?pag='.$pag.'&numpag='.$maxpag.$tarea.'">'.$maxpag.'</a>&nbsp;';
	} else {
		$base = ($numpag == 1) ? $numpag : $numpag-1;
		for ($i = $base;$i <= ($base+2); $i++) {
			echo "<a href=\"index.php?pag=$pag&numpag=$i".$tarea."\">$i</a>&nbsp;";
		}
	}
	echo '</p></div>';
}
?>
