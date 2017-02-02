<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SIDEBARSECTION_TAG', 'tag');
define ('GD_COMPACTSIDEBARSECTION_TAG', 'compact-tag');

class FullTagSidebarSettings {
	
	public static function get_components($section) {

		$ret = array();

		switch ($section) {

			case GD_SIDEBARSECTION_TAG:

				$ret[] = GD_TEMPLATE_TAGSOCIALMEDIA;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_TAGINFO;
				$ret = apply_filters('gd_template:sidebar_tag:components', $ret, $section);
				break;

			case GD_COMPACTSIDEBARSECTION_TAG:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_TAGLEFT;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_TAGRIGHT;
				break;
		}
		
		return $ret;
	}
}