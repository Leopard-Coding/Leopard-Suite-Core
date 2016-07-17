<?php
/**
 * Shows understandable designed errors
 *
 * @copyright ©Leopard
 * @license http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
 *
 * @author Julian Pfeil
 */ 
namespace LSC\lib\system\error;
 
class ErrorHandler
{
	public function __construct()
	{
		set_error_handler([$this, 'errorHandler']);
		register_shutdown_function([$this, 'shutdownHandler']);
		set_exception_handler([$this, 'exceptionHandler']);
	}

	public function errorHandler($ErrorLevel, $ErrorMessage, $ErrorFile, $ErrorLine)
	{
		$ErrorTrace = '';
		$Trace = array_reverse(debug_backtrace());
		array_pop($Trace);
		
		$i = 0;
		foreach ($Trace as $Item) {
		$ErrorTrace .= '#'.$i.' '.(isset($Item['file']) ? $Item['file'] : '<unknown file>').'('.(isset($Item['line']) ? $Item['line'] : '<unknown line>').'): '.$Item['function'].'()'."\n";
		}
		$ErrorTrace .= '{main}';
		$this->showError($ErrorLevel, $ErrorMessage, $ErrorFile, $ErrorLine, $ErrorTrace);
		
		return;
	}

	public function shutdownHandler()
	{
		$Error = error_get_last();
		if ($Error['type'] === E_ERROR) {
			if (ob_get_length() > 0) {
				ob_end_clean();
			}
			$this->showError($Error['type'], $Error['message'], $Error['file'], $Error['line'], null);
		}
		
		return;
	}

	public function exceptionHandler($Exception)
	{
		$this->showError($Exception->getCode(), $Exception->getMessage(), $Exception->getFile(), $Exception->getLine(), $Exception->getTraceAsString());
		
		return;
	}
	
	private function showError($ErrorLevel, $ErrorMessage, $ErrorFile, $ErrorLine, $ErrorTrace)
	{
		$ErrorId = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
		
		$FileName = __LSC_ABSOLUTE_DIR__.'logs/'.date('d-m-o').'.txt';
		$ErrorLogEntry = '['.$ErrorId.'] Error '.$ErrorLevel."\n";
		$ErrorLogEntry .= $ErrorMessage."\n";
		$ErrorLogEntry .= 'File: '.$ErrorFile."\n";
		$ErrorLogEntry .= 'Line: '.$ErrorLine."\n";
		$ErrorLogEntry .= 'Trace: '."\n".$ErrorTrace."\n\n";
		file_put_contents($FileName, $ErrorLogEntry, FILE_APPEND);
		
		$ErrorOutput = '		<style>';
		$ErrorOutput .= '			div.error {';
		$ErrorOutput .= '				margin: 16px 32px;';
		$ErrorOutput .= '				width: auto';
		$ErrorOutput .= '			}';
		$ErrorOutput .= '		</style>';
		$ErrorOutput .= '		<div class="error">';
		$ErrorOutput .= '			<div class="error-head">';
		$ErrorOutput .= '				An error occured. Sorry.';
		$ErrorOutput .= '			</div>';
		$ErrorOutput .= '			<div class="error-body">';
		$ErrorOutput .= '				Information: <br />';
		$ErrorOutput .= '				ID: <span class="error-id">'.$ErrorId.'</span><br />';
		$ErrorOutput .= '				Please tell the owner the id to identificate the error.<br />';
		$ErrorOutput .= '			</div>';
		$ErrorOutput .= '		</div>';
		echo $ErrorOutput;
		
		return;
	}
}
