<?php
class PoPEngine_Installation {

	function system_build() {

		// Comment Leo 17/07/2017: instead of executing 'install' whenever in the back-end and the pop_version changes,
		// we create a build page to be executed, statically, even in DEV
		do_action('PoP:system-build');

		// $version = pop_version();
		// $saved_version = get_option('PoP:version');

		// // Install if the current version has changed, or if forcing an update passing a parameter in the request
		// if (!$saved_version || $version > $saved_version || (has_role('administrator') && $_REQUEST['action'] == 'pop_install')){
		 	
		// 	do_action('PoP:system-build', $version);
			
		// 	//Update Version	
		// 	update_option('PoP:version', $version);
		// 	$this->install();
		// }
	}

	function system_build_server() {

		do_action('PoP:system-build:server');
	}

	function system_generate() {

		do_action('PoP:system-generate');
	}

	function system_generate_theme() {

		do_action('PoP:system-generate:theme');
	}

	function system_activateplugins() {

		do_action('PoP:system-activateplugins');
	}

	function system_install() {

		// Save the new version on the DB
		$version = pop_version();
		update_option('PoP:version', $version);

		// Execute install everywhere
		do_action('PoP:system-install');
	}
}