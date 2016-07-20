<?php
namespace LSC\lib\system\util;

class PlaceholderUtil
{
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
