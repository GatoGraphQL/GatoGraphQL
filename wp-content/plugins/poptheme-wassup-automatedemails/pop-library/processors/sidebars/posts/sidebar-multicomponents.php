<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-automatedemails-post'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-automatedemails-featuredimage'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-automatedemails-featuredimagevolunteer'));

class PoPTheme_Wassup_AE_Template_Processor_PostMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE;
				// $ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG;
				// $ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_PostMultipleSidebarComponents();