<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_URE_Engine_Utils {

	public static function get_vars($vars) {

		// global-state variables already set by PoP_WPAPI
		if ($vars['global-state']['is-author']/*is_author()*/) {

			$author = $vars['global-state']['author']/*global $author*/;
			if (gd_ure_is_community($author)) {

				$source = $_REQUEST[GD_URLPARAM_URECONTENTSOURCE];
				$sources = array(
					GD_URLPARAM_URECONTENTSOURCE_ORGANIZATION,
					GD_URLPARAM_URECONTENTSOURCE_COMMUNITY,
				);
				if (!in_array($source, $sources)) {
					$source = gd_ure_get_default_contentsource();
				}
				
				$vars['source'] = $source;
			}
		}

		return $vars;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:get_vars', array('PoP_URE_Engine_Utils', 'get_vars'));