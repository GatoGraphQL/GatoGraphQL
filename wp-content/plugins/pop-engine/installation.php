<?php
class PoPEngine_Installation {

	function install() {

		$version = pop_version();
		$saved_version = get_option('PoP:version');

		// Install if the current version has changed, or if forcing an update passing a parameter in the request
		if ($version > $saved_version || !$saved_version || (has_role('administrator') && $_REQUEST['action'] == 'pop_install')){
		 	
			do_action('PoP:install', $version);
			
			//Update Version	
			update_option('PoP:version', $version);
			$this->install();
		}
	}

	function system_install() {

		do_action('PoP:system-install');
	}
}