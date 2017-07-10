<?php
class PoPFrontend_Installation {

	function system_install(){

		// Do not install immediately, but do it only at the end of everything, so that all plugins can inject their list of css-files
		global $pop_frontend_conversionmanager;
		add_action('wp_footer', array($pop_frontend_conversionmanager, 'generate'), 10000);
	}
}