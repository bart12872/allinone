<?php

require_once 'Sea/Shell.php';

class Sea_Shell_Ffmpeg2theora extends Sea_Shell {
	
	protected $_command='/usr/local/bin/ffmpeg2theora';
	
	
	public function info($file) {
		$this->addArg('--info');// controller
		$this->addArg($file);
		
		// on lance le processus
		$this->run();
		
		while($this->isRunning()) {sleep(1);}
		
		return Zend_Json::decode(stream_get_contents($this->getStdout()));
	}

}

?>