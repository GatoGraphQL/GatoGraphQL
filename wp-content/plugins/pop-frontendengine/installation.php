<?php
class PoPFrontend_Installation {

	function system_build(){

		global $pop_frontend_conversionmanager;
		// // Do not install immediately, but do it only at the end of everything, so that all plugins can inject their list of css-files
		// add_action('wp_footer', array($pop_frontend_conversionmanager, 'generate'), 10000);
		$pop_frontend_conversionmanager->generate();
	}
}