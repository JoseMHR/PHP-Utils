<?php
abstract class Config{
	protected static $host='localhost';
	protected static $user='root';
	protected static $pass='ejemplopass';
	protected static $dbName='ejemplo';
	protected static $charset='utf8';
	
	protected static function getHost(){
		return self::$host;
	}
	protected static function getUser(){
		return self::$user;
	}
	protected static function getPass(){
		return self::$pass;
	}
	protected static function getDbName(){
		return self::$dbName;
	}
	protected static function getCharset(){
		return self::$charset;
	}
	protected static function setHost($value){
		self::$host=$value;
	}
	protected function setUser($value){
		self::$user=$value;
	}
	protected function setPass($value){
		self::$pass=$value;
	}
	protected function setDbName($value){
		self::$dbName=$value;
	}
	protected function setCharset($value){
		self::$charset=$value;
	}
}
?>