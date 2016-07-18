<?php
/**
 * Used to create or convert strings
 *
 * @copyright ©Leopard
 * @license http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
 *
 * @author Julian Pfeil
 */ 
namespace LSC\lib\system\util;

class StringUtil
{
	public static function generateString($Length = null, $MinLength = null, $MaxLength = null, $UseLowerCharString = true, $UseUpperCharString = true, $UseCypherString = true, $UseSpecialCharString = false)
	{
		$CharString = '';
		$String = '';
		if ($Length !== null && $MinLength === null && $MaxLength === null) {
			$StringLength = $Length;
		} elseif ($Length === null && $MinLength !== null && $MaxLength !== null) {
			$StringLength = rand($MinLength, $MaxLength);
		} else {
			return false;
		}
		$LowerCharString = 'abcdefghijklmnopqrstuvxyz';
		$UpperCharString = 'ABCDEFGHIJKLMNOPQRSTUVXYZ';
		$CypherString = '0123456789';
		$SpecialCharString = '!"/()=?{[]}*+#<>|-_.:,;';
		if ($UseLowerCharString) {
			$CharString .= $LowerCharString;
		}
		if ($UseUpperCharString) {
			$CharString .= $UpperCharString;
		}
		if ($UseCypherString) {
			$CharString .= $CypherString;
		}
		if ($UseSpecialCharString) {
			$CharString .= $SpecialCharString;
		}
		
		$CharArray = str_split($CharString);
		$i = 0;
		while ($i < $StringLength) {
			$String .= $CharArray[rand(0, count($CharArray) - 1)];
			$i++;
		}
		
		return $String;
	}
	
	public static function checkLength($String, $MinLength, $MaxLength)
	{
		if (strlen($String) <= $MaxLength && strlen($String) >= $MinLength) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function checkLength($String, $MinLength, $MaxLength)
	{
		if (strlen($String) <= $MaxLength && strlen($String) >= $MinLength) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function checkASCII($String)
	{
		if (mb_check_encoding($String, 'ASCII')) {
			return true;
		} else {
			return false;
		}
	}
}
