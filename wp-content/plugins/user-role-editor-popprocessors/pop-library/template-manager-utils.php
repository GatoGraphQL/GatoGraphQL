<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * User Role Editor plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_TemplateManager_Utils {

	public static function add_source($url, $source) {

		// Add the source only if it's not the default one
		if ($source != gd_ure_get_default_contentsource()) {

			$url = add_query_arg(GD_URLPARAM_URECONTENTSOURCE, $source, $url);
		}

		return $url;
	}
}