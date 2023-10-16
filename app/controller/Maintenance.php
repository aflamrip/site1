<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Maintenance extends Controller {
	public function process() {
		$AuthUser = $this->getVariable("AuthUser");
		$Route = $this->getVariable("Route");
		$Config['title'] 		= __('Maintenance mode');
		$Config['description'] 	= __('Maintenance mode'); 
        $Config['url']          = APP.'/maintenance'; 
		$this->setVariable("Config", $Config);
		$this->view('maintenance', 'app');
	}
}
