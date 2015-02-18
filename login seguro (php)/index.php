<?php

if ($_SERVER['REQUEST_METHOD']=='POST') { 
    include('php_lib/config.ini.php'); 
    include('php_lib/login.class.php'); 
    $Login=new Login();

    if ($Login->login($_POST['usuario'],$_POST['password'])) {
		
		$usuario = $_SESSION['NOMBRE']['name'];
		$tipo_usuario = $_SESSION['TIPO_USUARIO']['rol'];
		$log->addLine(array('El día '.$dia.' de '.$escribe_mes.' de '.$anio.' '.$usuario.' inició sesión a las '.$hora.''));
				
        if($tipo_usuario == 'conserje'){
		  header('Location: asistencias.php');
		}else{
		  header('Location: inicio.php');
		}
        die();
    } else {

        $mensaje='Usuario o contraseña incorrecto.';
    }
} 

?>

<!DOCTYPE html>
<html>
<head>

<!--LOGIN FORM-->


<!-- FIN LOGIN FORM-->


</body>
</html>