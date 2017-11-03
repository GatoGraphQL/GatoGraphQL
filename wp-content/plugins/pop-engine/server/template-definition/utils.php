<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_TemplateIDUtils {

	public static function get_manager() {

		global $pop_templateidmanager;
		return $pop_templateidmanager;
	}

	public static function set_namespace($namespace) {

		self::get_manager()->set_namespace($namespace);
	}

	/**
	 * Function used to create a definition for a template. Needed for reducing the filesize of the html generated for PROD
	 * Instead of using the name of the $template_id, we use a unique number in base 36, so the name will occupy much lower size
	 * Comment Leo 27/09/2017: Changed from $template_id to only $id so that it can also be used with ResourceLoaders
	 */
	public static function get_template_definition($id/*$template_id*/, $mirror = false) {

		return self::get_manager()->get_template_definition($id, $mirror);
	}
}