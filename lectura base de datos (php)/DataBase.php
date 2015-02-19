<?php
class DataBase extends Config{
	private static $connection;
	protected static $_singleton;

	public static function getInstance(){
		if(is_null(self::$_singleton)){
			self::$_singleton = new DataBase();
		}
		return self::$_singleton;
	}
	protected function __construct(){
		self::$connection=new PDO("mysql:host=".Config::getHost().";dbname=".Config::getDbName().";charset=".Config::getCharset(), Config::getUser(), Config::getPass()); 
	}
	public static function getPdo(){
		return self::$connection;
	}
}
?>