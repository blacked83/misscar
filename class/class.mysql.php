<?php
require_once __DIR__ . '/../config.php';
class mysqlConex {
    //Variables Necesarias para el Recordset
    private $host = MYSQL_HOST;
    private $usuario = MYSQL_USER;
    private $clave = MYSQL_PASS;
    private $bd = MYSQL_BD;
    private $ssl = MYSQL_CERT; 
    private $conexion;
    public $table;

	//Función que permite conectar la BD 
    function conectar(){
        $this->conexion = mysqli_init();
        if($this->conexion){
            if(!empty($this->ssl)){
                $this->conexion->ssl_set(null, null, $this->ssl, null, null);
                $mysqlSSL = MYSQLI_CLIENT_SSL;
            }
            if(!@$this->conexion->real_connect($this->host, $this->usuario, $this->clave, $this->bd, 3306, $mysqlSSL)){
                logs("Database connection error #" . mysqli_connect_errno() . ": " . mysqli_connect_error());
                return false;
            }
            $this->conexion->set_charset('utf8');
        }
        return !is_null($this->conexion); 
    }

    //Función que permite cesonectar la BD 
    function desconectar(){
        if(is_object($this->table))
            $this->table->close();
        if(is_object($this->conexion))
            $this->conexion->close();
        $this->table = null;
        $this->conexion = null;
    }

    //Función que permite optimizar una tabla
	function optimizartabla(string $tabla){
        $this->conectar();
        $sql = "OPTIMIZE TABLE $tabla;";
        $this->conexion->query($sql);
	}
    
	//Función que permite truncar una tabla
	function truncartabla(string $tabla){
        $this->conectar();
        $sql = "TRUNCATE TABLE $tabla;";
        $this->conexion->query($sql);
    }

    //Función que permite realizar una consulta a la BD
    function query($sql, string $function){
        if(is_object($this->conexion)){
            $exec = @$this->conexion->query($sql);
            if(!$exec)
                logs("Function $function(): " . $this->conexion->error . ", Action: $sql");
            return $exec;
        }else{
            logs("Function $function(): you must establish a connection first, Action: $sql");
            return false;
        }
    }

    // Función para iniciar una transacción 
    function transactionStart(){
        if(!empty($this->conexion))
            $this->conexion->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
    }

    // Función para finalizar una transacción 
    function transactionEnd(bool $commit = true){
        if(!empty($this->conexion))
            if($commit){
                $this->conexion->commit();
            }else{
                $this->conexion->rollback();
            }
    }

}

?>