<?php

class PoP_Engine_TemplateUtils {

	public static function validate_pop_loaded($json = false) {

		// If the theme was not loaded because some depended-upon plugin was not loaded, then do nothing, just show an error
		if (!pop_loaded()) {

			if ($json) {
				
				header('Content-type: application/json');
				echo json_encode(array(
					'error' => POP_MSG_STARTUPERROR,
				));
			}
			else {
				
				echo POP_MSG_STARTUPERROR;
			}
			exit;
		}
	}

	public static function maybe_redirect() {

		$pop_module_settingsmanager = PoPEngine_Module_SettingsManager_Factory::get_instance();
		if ($redirect = $pop_module_settingsmanager->get_redirect_url()) {

			if ($query = $_SERVER['QUERY_STRING']) {

				$redirect .= '?'.$query;
			}

			$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
			$cmsapi->redirect($redirect); 
			exit;
		}
	}

	public static function generate_data() {

		$engine = PoP_Engine_Factory::get_instance();
		$engine->generate_data();
	}
}