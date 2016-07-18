<?php
/**
 * Replaces placeholder in content
 *
 * @copyright ©Leopard
 * @license http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
 *
 * @author Julian Pfeil
 */ 
namespace LSC\lib\system\util;

class PlaceholderUtil
{
	/**
	 * Replaces placeholder in content
	 *
	 * @param array $Placeholders Array where keys are placeholder-names and values are the replacements
	 * @param string $Source Path to file with content or directly the content
	 * @param boolean $File If content is read out of file
	 *
	 * @return void|string $Source IF content is not read out of file, returns edited content
	 */ 
	public static function replace(array $Placeholders, $Source, $File = true)
	{
		if ((bool) $File) {
			$FilePath = $Source;
			$Source = file_get_contents($FilePath);
		}
		foreach ($Placeholders as $Placeholder => $Replacement) {
			$Content = str_replace('{$'.$Placeholder.'}', $Replacement, $Content);
		}
		if ((bool) $File) {
			file_put_contents($FilePath, $Source);
			
			return;
		} else {
			return $Source;
		}
	}
}
