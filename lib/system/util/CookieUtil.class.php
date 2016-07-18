<?php
/**
 * Provides cookie-administration
 *
 * @copyright ©Leopard
 * @license http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
 *
 * @author Julian Pfeil
 */ 
namespace LSC\lib\system\util;

class CookieUtil
{
	/**
	 * Sets cookie or session
	 * 
	 * @api
	 *
	 * @param string $Name Name of cookie or session
	 * @param string $Value Value of cookie or session
	 * @param string $Type Defines if cookie or session
	 * @param string $Lifetime Defines cookies lifetime
	 *
	 * @return void
	 */
	public static function add($Name, $Value, $Type = 'COOKIE', $Lifetime = 60*60*24*365*5)
	{
		if($Type == 'COOKIE') {
			setcookie($Name, json_encode($Value), time() + $Lifetime);
		} elseif ($Type == 'SESSION') {
			$_SESSION[$Name] = $Value;
		}
		
		return;
	}
	
	/**
	 * Deletes cookie or session
	 * 
	 * @api
	 *
	 * @param string $Name Name of cookie or session
	 *
	 * @return void
	 */
	public static function delete($Name)
	{
		if(isset($_COOKIE[$Name])) {
			setcookie($Name, null, time() - 1);
		} else {
			unset($_SESSION[$Name]);
		}
		
		return;
	}
	
	/**
	 * Returns cookie- or session-value
	 * 
	 * @api
	 *
	 * @param string $Name Name of cookie or session
	 *
	 * @return string|array Returns cookie- or session-value
	 */
	public static function read($Name)
	{
		if(isset($_COOKIE[$Name])) {
			return json_decode($_COOKIE[$Name], true);
		} else {
			return $_SESSION[$Name];
		}
	}
	
	/**
	 * Checks if cookie or session with this name is set
	 * 
	 * @api
	 *
	 * @param string $Name Name of cookie or session
	 *
	 * @return boolean Returns true if cookie or session is set, false if not
	 */
	public static function checkSet($Name)
	{
		if(isset($_COOKIE[$Name]) || isset($_SESSION[$Name])) {
			return true;
		} else {
			return false;
		}
	}
}
