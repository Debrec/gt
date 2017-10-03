<?php
	date_default_timezone_set ( "America/Argentina/Buenos_Aires" );
	
    class InicioActividad {
        public $tabla = 'inicio_actividad';
        private $mysqli = false;
        
        public function __construct() {
            include('conectar.php');
            $this->mysqli = $mysqli;
        }
        
        public function __destruct() {
            $this->mysqli->close();
        }
        
        public function inicio_actividad($userid) {
            if (isset($userid)) {
                $fecha=date("Y-m-d H:i:s");
                $result = selectfield($this->tabla,'userid',$userid);
                if ($result) {
                    $query="UPDATE ".$this->tabla." set  fecha='$fecha' where userid=$userid";
                    if (! $this->mysqli->query($query)) {
                        error_log("ERROR: No se pudo ejecutar $query. " . $mysqli->error,0);		
                    }
                } else {
                    $query="INSERT INTO ".$this->tabla." (userid,fecha) VALUES ('$userid','$fecha')";
                    if (! $this->mysqli->query($query)) {
                        error_log("ERROR: No se pudo ejecutar $query. " . $mysqli->error,0);		
                    }
                }
            }
        }
	
        public function obtener_fecha($userid) {
            if (isset($userid)) {
               /* include('./conectar.php');
                $query = "select fecha from inicio_actividad where userid=$userid";
                if ($result = $mysqli->query($query)) {
                    $row = $result->fetch_array()[0];
                }*/
                return selectfield($this->tabla,'fecha',$userid);
            }
        }
    }
    $inicio_actividad = new InicioActividad();
?>
