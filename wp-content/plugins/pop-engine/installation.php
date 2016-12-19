<?php
class PoPEngine_Installation {

	function install(){

		$version = pop_version();
		$saved_version = get_option('PoP:version');
		if ($version > $saved_version || !$saved_version){
		 	
			do_action('PoP:install', $version);
			
			//Update Version	
			update_option('PoP:version', $version);
		}
	}
}