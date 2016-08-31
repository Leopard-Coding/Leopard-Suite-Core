<?php
namespace LSC\lib\system\util;

class FileUtil
{
	public static function extractZip($ZipArchive, $ExtractPath = '')
	{
		$zip = new \ZipArchive;
		if ($zip->open($ZipArchive) === true) {
			$zip->extractTo($ExtractPath);
			$zip->close();
		} else {
			try {
				throw new Exception('Zip-archive \''.$ZipArchive.'\' does not exist');
			} catch (\Exception $e) {
				throw $e;
			}
		}
		
		return;
	}
	
	public static function makeDirectory($Directory)
	{
		mkdir($Directory);
		
		return;
	}
	
	public static function deleteDirectory($Directory)
	{
		$DirectoryHandler = opendir($Directory);  
		while($File = readdir($DirectoryHandler)) {  
			if(!is_dir($Directory.$File) && $File != '.' && $File != '..') {
				unlink($Directory.$File); 
			}
		}
		closedir($DirectoryHandler);  
		rmdir($Directory);
		
		return;
	}
}
