<?php
namespace LSC\lib\system\util;

class CookieUtil
{
	public static function add($Name, $Value, $Type = 'COOKIE', $Lifetime = 60*60*24*365*5)
	{
		if($Type == 'COOKIE') {
			setcookie($Name, json_encode($Value), time() + (int) $Lifetime);
		} elseif ($Type == 'SESSION') {
			$_SESSION[$Name] = $Value;
		}
		
		return;
	}
	
	public static function delete($Name)
	{
		if(isset($_COOKIE[$Name])) {
			setcookie($Name, null, time() - 1);
		} elseif(isset($_SESSION[$Name])) {
			unset($_SESSION[$Name]);
		}
		
		return;
	}
	
	public static function read($Name)
	{
		if(isset($_COOKIE[$Name])) {
			return json_decode($_COOKIE[$Name], true);
		} else {
			return $_SESSION[$Name];
		}
	}
	
	public static function checkIfSet($Name)
	{
		if(isset($_COOKIE[$Name]) || isset($_SESSION[$Name])) {
			return true;
		} else {
			return false;
		}
	}
}
