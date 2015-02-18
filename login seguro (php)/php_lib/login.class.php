<?php
/**
 * Clase Login para validar un usuario comprobando su usuario (o email) y contraseña
 */
class Login {
    
    public $tabla='nombre_tabla'; //nombre de la tabla usuarios
    public $campo_nombre='nombre'; 
	public $campo_apellidos='apellidos'; 
    public $campo_usuario='email'; //campo que contiene los datos de los usuarios (se puede usar el email)
	public $tipo_usuario='tipo_usuario'; 
    public $id_usuario='dni'; 	 
    public $campo_clave='password'; //campo que contiene la contraseña
    public $metodo_encriptacion='md5'; //método utilizado para almacenar la contraseña. Opciones: sha1, md5, o texto
    public $email_usu='email'; //método utilizado para almacenar la contrasela. Opciones: sha1, md5, o texto			


    private $link; //identificador de la conexión mysql que usamos
    
    /**
     * establecemos el método  de construccion de la clase que se llamará al crear el objeto. Conectamos a la base de datos
     * @return bool
     */
   public function __construct() {
       //1 - conectamos a la base de datos utilizando los parámetros globales
       // deberiamos utilizar una clase de acceso a la base de datos con el patrón singleton, pero lo dejo para otro tutorial
        $this->link =  mysql_connect(SERVIDOR_MYSQL, USUARIO_MYSQL, PASSWORD_MYSQL);
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		
        if (!$this->link) {
            trigger_error('Error al conectar al servidor mysql: ' . mysql_error(),E_USER_ERROR);
        }
        
        // Seleccionar la base de datos activa
        $db_selected = mysql_select_db(BASE_DATOS,$this->link);
        if (!$db_selected) {
            trigger_error ('Error al conectar a la base de datos: ' . mysql_error($this->link),E_USER_ERROR);
        }
        
        return true;
        
   }
   
   //el metodo de destrucción al destruir el objeto
   public function __destruct() {
       mysql_close($this->link);
   }
   
   
    /**
     * valida un usuario y contraseña
     * @param string $usuario
     * @param string $password
     * @return bool
     */
    public function login($usuario, $password) {

        //usuario y password tienen datos?
        if (empty($usuario)) return false;
        if (empty ($password)) return false;

        //2 - preparamos la consulta SQL a ejecutar utilizando sólo el usuario y evitando ataques de inyección SQL.
        $query='SELECT '.$this->campo_usuario.', '.$this->id_usuario.', '.$this->campo_nombre.', '.$this->campo_apellidos.', '.$this->tipo_usuario.', '.$this->campo_clave.' FROM '.$this->tabla.' WHERE '.$this->campo_usuario.'="'.  mysql_real_escape_string($usuario).'" LIMIT 1 '; //la tabla y el campo se definen en los parametros globales
        $result = mysql_query($query);
        if (!$result) {
            trigger_error('Error al ejecutar la consulta SQL: ' . mysql_error($this->link),E_USER_ERROR);
        }


        //3 - extraemos el registro de este usuario
        $row = mysql_fetch_assoc($result);

        if ($row) {
            //4 - Generamos el hash de la contraseña encriptada para comparar o lo dejamos como texto plano
            switch ($this->metodo_encriptacion) {
                case 'sha1'|'SHA1':
                    $hash=sha1($password);
                    break;
                case 'md5'|'MD5':
                    $hash=md5($password);
                    break;
                case 'texto'|'TEXTO':
                    $hash=$password;
                    break;
                default:
                    trigger_error('El valor de la propiedad metodo_encriptacion no es válido. Utiliza MD5 o SHA1 o TEXTO',E_USER_ERROR);
            }

            //5 - comprobamos la contraseña
            if ($hash==$row[$this->campo_clave]) {
                @session_start();
                $_SESSION['USUARIO']=array('user'=>$row[$this->campo_usuario]); //almacenamos en memoria el usuario
				$_SESSION['NOMBRE']=array('name'=>$row[$this->campo_nombre]); //almacenamos en memoria el usuario
				$_SESSION['APELLIDOS']=array('surname'=>$row[$this->campo_apellidos]); //almacenamos en memoria el usuario				
				$_SESSION['TIPO_USUARIO']=array('rol'=>$row[$this->tipo_usuario]); //almacenamos en memoria el tipo de usuario
				$_SESSION['ID_USUARIO']=array('idu'=>$row[$this->id_usuario]); //almacenamos en memoria el usuario
				$_SESSION['EMAIL']=array('email_usu'=>$row[$this->email]); //almacenamos en memoria el usuario

				
                // en este punto puede ser interesante guardar más datos en memoria para su posterior uso, como por ejemplo un array asociativo con el id, nombre, email, preferencias, ....
                return true; //usuario y contraseña validadas
            } else {
                @session_start();
                unset($_SESSION['USUARIO']); //destruimos la session activa al fallar el login por si existia
				unset($_SESSION['NOMBRE']);
				unset($_SESSION['APELLIDOS']);				
				unset($_SESSION['TIPO_USUARIO']);
				unset($_SESSION['ID_USUARIO']);
				unset($_SESSION['EMAIL']);
				
                return false; //no coincide la contraseña
            }
        } else {
            //El usuario no existe
            return false;
        }

    }
    
    


    /**
     * Verifica si el usuario está logeado
     * @return bool
     */
    public function estoy_logeado () {
        @session_start(); //inicia sesion (la @ evita los mensajes de error si la session ya está iniciada)

        if (!isset($_SESSION['USUARIO'])) return false; //no existe la variable $_SESSION['USUARIO']. No logeado.
        if (!is_array($_SESSION['USUARIO'])) return false; //la variable no es un array $_SESSION['USUARIO']. No logeado.
        if (empty($_SESSION['USUARIO']['user'])) return false; //no tiene almacenado el usuario en $_SESSION['USUARIO']. No logeado.
		
		
		
		
        //cumple las condiciones anteriores, entonces es un usuario validado
        return true;

    }

    /**
     * Vacia la sesion con los datos del usuario validado
     */
    public function logout() {
        @session_start(); //inicia sesion (la @ evita los mensajes de error si la session ya está iniciada)
        unset($_SESSION['USUARIO']); //eliminamos la variable con los datos de usuario;
        session_write_close(); //nos asegurmos que se guarda y cierra la sesion
        return true;
    }    
        
}




    
?>