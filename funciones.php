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

function leap($year)
{
	$lpy=0;
	if($year%1000== 0){
		if($year%400==0)$lpy=1;
	} else if($year%100==0){
		if($year/40==0)$lpy=1;
	}
	else{
		if($year/4==0)$py=1;
	}
       
    return($lpy);
}

function diadelmes($ano,$mes) {
	$dias = array(31,28+leap($ano),31,30,31,30,31,31,30,31,30,31);
	return $dias[$mes];
}

function fecha($cod,$ano, $mes,$dia,$hora,$min,$sec) {
			$anoa = date("Y");
			$mesa = date("m");
			$diaa = date("d");
			
			echo "<span> Año : ";
			echo "<select name=\"ano$cod\" onchange=\"this.form.submit()\">\n";	
			if ($ano == $anoa) {
				$sva = 1; 	
			} else {
				$sva = 0;
			}
			if ($mes == "01") {
				$prev=$anoa-1;
				echo "<option ".($sva ? "selected" : "")." value=$prev>$prev</option>\n";
			}
			echo "<option ".($sva ? "selected" : "")." value=$ano>$ano</option>\n";
			echo "</select>\n";
			echo "</span>\n";
			 
			echo "<span> Mes : ";
			echo "<select name=\"mes$cod\" onchange=\"this.form.submit()\">\n";
			if ($dia == 1) {
				if ($mesa != 1) {
					if (($mesa - 1) <10) {
						$prev=sprintf("0%s",$mesa-1);
					} else {
						$prev=$mesa-1;
					}
					echo "<option ".(($mes == $prev) ? "selected": ""). " value=$prev>$prev</option>\n";
				} else {
					if ($ano == ($anoa-1)) {
						$mes = 12;
					} else {
						$mes = "01";
					}
				} 
			} 
			echo "<option ".(($mes == $mesa) ? "selected": ""). " value=$mes>$mes</option>\n";
			echo "</select>";	
			echo "</select>\n";
			echo "</span>\n";

			echo "<span> Día : ";
			echo "<select name=\"dia$cod\" onchange=\"this.form.submit()\">\n";	
			if ($dia != 1) {
				if ($dia == $diaa) {
					$prev=$dia-1;
					$svd = 1;
				} else {
					$prev = $dia;
					$dia = $diaa;
					$svd = 0;
				}
				echo "<option ".(!$svd ? "selected" : "")." value=$prev>$prev</option>\n";
			} else if ($mes == $mesa-1){
				$dia = diadelmes($ano,$mes);
			}
			echo "<option ".($svd ? "selected" : "")." value=$dia>$dia</option>\n";
			echo "</select>\n";
			echo "</span>\n";
			
			echo "<br>\n";
			
			echo "<span> Hora : ";
			echo "<select name=\"hora$cod\">\n";
				if ($svd) {
					if ($hora <= date("H")) {			
						$sel = $hora;
					} else {
						$sel = date("H");
					}
					$fin = date("H");		
				} else {
					$fin = 23;
					$sel = $hora;
				}
				for ($i=0;$i<=$fin;$i++) {
					if ($i<10) {
						$val=sprintf("0%s",$i);
					} else {
						$val=$i;
					}
					echo "<option ".($i==$sel ? "selected" : "" )." value=$val>$val</option>";
				}
			echo "</select>\n";
			echo "</span>\n";
			
			echo "<span> Minutos : ";
			echo "<select name=\"min$cod\">\n";
				if ($svd) {
					if ($min <= date("i")) {			
						$sel = $min;
					} else {
						$sel = date("i");
					}
					$fin = date("i");		
				} else {
					$fin = 59;
					$sel = $min;
				}
				for ($i=0;$i<=$fin;$i++) {
					if ($i<10) {
						$val=sprintf("0%s",$i);
					} else {
						$val=$i;
					}
					echo "<option ".($i==$sel ? "selected" : "" )." value=$val>$val</option>";
				}
			echo "</select>\n";
			echo "</span>\n";
			
			echo "<span> Segundos : ";
			echo "<select name=\"sec$cod\">\n";
				if ($svd) {
					if ($sec <= date("s")) {			
						$sel = $sec;
					} else {
						$sel = date("s");
					}
					$fin = date("s");		
				} else {
					$fin = 59;
					$sel = $sec;
				}
				for ($i=0;$i<=$fin;$i++) {
					if ($i<10) {
						$val=sprintf("0%s",$i);
					} else {
						$val=$i;
					}
					echo "<option ".($i==$sel ? "selected" : "" )." value=$val>$val</option>";
				}
			echo "</select>\n";
			echo "</span>\n";
}


function horas($cod,$hora,$min,$sec) {
			$curh = date("H");			
			$curm = date("i");
			$curs = date("s");
			
			echo "<span> Hora : ";
			echo "<select id=\"hora$cod\" name=\"hora$cod\" onchange=\"updatehoras('$cod',$curh,$curm,$curs);\">\n";
				/*if ($hora < $horaa) {			
					$sel = $hora;
					$svh = 1;
				} else {
					$sel = $horaa;
					$svh=0;
				}
				$fin = $horaa;		
				for ($i=0;$i<=$fin;$i++) {
					if ($i<10) {
						$val=sprintf("0%s",$i);
					} else {
						$val=$i;
					}
					echo "<option ".($i==$sel ? "selected" : "" )." value=$val>$val</option>";
				}*/
			echo "</select>\n";
			echo "</span>\n";
			
			echo "<span> Minutos : ";
			echo "<select id=\"min$cod\" name=\"min$cod\" onchange=\"updatehoras('$cod',$curh,$curm,$curs);\">\n";
				/*if ($svh) {
					$fin = 59;
					$sel = $min;
					$svm=1;
				} else {
					$fin = $mina;
					if ($min <$fin) {
						$svm=1;
						$sel = $min;
					} else {
						$svm=0;
						$sel=$mina;
					}
				}
				
				/*if ($svh && ($min <= $fin)) {
					$sel = $min;
				} else if ($svh && ) {
					if ($min <= $fin) {
					$sel = $min;
					$svm=0;
				}*//*
				for ($i=0;$i<=$fin;$i++) {
					if ($i<10) {
						$val=sprintf("0%s",$i);
					} else {
						$val=$i;
					}
					echo "<option ".($i==$sel ? "selected" : "" )." value=$val>$val</option>";
				}*/
			echo "</select>\n";
			echo "</span>\n";
			
			echo "<span> Segundos : ";
			echo "<select id=\"sec$cod\" name=\"sec$cod\">\n";
				/*if ($svm) {
					$fin = 59;
					$sel = $sec;
				} else {
					$fin = $seca;
					if ($sec <$fin) {
						$sel = $sec;
					} else {
						$sel=$seca;
					}
				}
				/*if ($svh || $svm) {
					$fin = 59;
				} else {
					$fin = $seca;
				}
				
				if ($sec <= $fin) {
					$sel = $sec;
				} else {
					$sel = $seca;
				}

/*				if ($sec < $seca) {			
					$sel = $sec;
				} else {
					$sel = $seca;
				}
				$fin = $seca;*/		/*
				for ($i=0;$i<=$fin;$i++) {
					if ($i<10) {
						$val=sprintf("0%s",$i);
					} else {
						$val=$i;
					}
					echo "<option ".($i==$sel ? "selected" : "" )." value=$val>$val</option>";
				}*/
			echo "</select>\n";
			echo "</span>\n";
}
?>



