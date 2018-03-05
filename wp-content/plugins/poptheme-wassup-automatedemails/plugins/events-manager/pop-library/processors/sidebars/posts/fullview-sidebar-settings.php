<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_EVENT', 'compact-automatedemails-event');

class EM_AE_FullViewSidebarSettings {
	
	public static function get_components($section) {

		$ret = array();

		switch ($section) {

			case GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_EVENT:

				// Only if the Volunteering is enabled
				if (defined('POP_GENERICFORMS_PAGE_VOLUNTEER')) {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER;
				}
				else {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE;
				}
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_EVENT;
				break;
		}
		
		return $ret;
	}
}