<?php
include './Config.php';
include './DataBase.php';

DataBase::getInstance();
$pdo=DataBase::getPdo();

$return=$pdo->query("SELECT * FROM `pruebas`");
if(!empty($return)){
	$return=$return->fetchAll(PDO::FETCH_ASSOC);
}else{
	echo "no hay resultados.";
	$return=Array();
}
echo json_encode($return);
?>