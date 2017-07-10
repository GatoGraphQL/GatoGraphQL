<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_POST', 'compact-automatedemails-post');

class AE_FullViewSidebarSettings {
	
	public static function get_components($section) {

		$ret = array();

		switch ($section) {

			case GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_POST:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST;
				break;
		}
		
		return $ret;
	}
}