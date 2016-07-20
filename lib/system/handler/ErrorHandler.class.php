<?php
namespace LSC\lib\system\handler;
 
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
		$ErrorTrace = self::getTrace(debug_backtrace());
		
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
	
	private static function getTrace($DebugBacktrace)
	{
		$ErrorTrace = '';
		$Trace = array_reverse($DebugBacktrace);
		array_pop($Trace);
		$Trace = array_reverse($Trace);
		$i = 0;
		foreach ($Trace as $Item) {
			$ErrorTrace .= '#'.$i.' '.(isset($Item['file']) ? $Item['file'] : '<unknown file>').'('.(isset($Item['line']) ? $Item['line'] : '<unknown line>').'): '.$Item['function'].'()'."\n";
			$i++;
		}
		$ErrorTrace .= '#'.$i.' {main}';
		
		return $ErrorTrace;
	}
	
	private static function getErrorId($Length = 16)
	{
		$ErrorId = '';
		$i = 0;
		while($i < $Length) {
			$ErrorId .= rand(0, 9);
			$i++;
		}
	}
	
	private function showError($ErrorLevel, $ErrorMessage, $ErrorFile, $ErrorLine, $ErrorTrace)
	{
		$ErrorId = self::getErrorId();
		$ErrorOutput = '';
		$FileName = __LSC_ABSOLUTE_DIR__.'logs/'.date('m-d-o').'.txt';
		if (is_writable(__LSC_ABSOLUTE_DIR__.'logs/')) {
			$ErrorLogEntry = '['.$ErrorId.'] Error '.$ErrorLevel."\n";
			$ErrorLogEntry .= $ErrorMessage."\n";
			$ErrorLogEntry .= 'File: '.$ErrorFile."\n";
				$ErrorLogEntry .= 'Line: '.$ErrorLine."\n";
			if ($ErrorTrace !== null) {
				$ErrorLogEntry .= 'Trace: '."\n".$ErrorTrace."\n\n";
			}
			file_put_contents($FileName, $ErrorLogEntry, FILE_APPEND);
		
			$ErrorOutput .= '		<style>';
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
		} else {
			$ErrorOutput .= '		<style>';
			$ErrorOutput .= '			div.error {';
			$ErrorOutput .= '				margin: 16px 32px;';
			$ErrorOutput .= '				width: auto';
			$ErrorOutput .= '			}';
			$ErrorOutput .= '		</style>';
			$ErrorOutput .= '		<div class="error">';
			$ErrorOutput .= '			<div class="error-head">';
			$ErrorOutput .= '				Fatal Error!';
			$ErrorOutput .= '			</div>';
			$ErrorOutput .= '			<div class="error-body">';
			$ErrorOutput .= '				The log directory '.__LSC_ABSOLUTE_DIR__.'logs/'.' is not writable! <br />';
			$ErrorOutput .= '			</div>';
			$ErrorOutput .= '		</div>';
		}
		echo $ErrorOutput;
		
		return;
	}
}
