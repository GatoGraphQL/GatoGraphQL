<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_TAGLEFT', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-tagleft'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_TAGRIGHT', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-tagright'));

class GD_Custom_Template_Processor_TagMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_TAGLEFT,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_TAGRIGHT,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_TAGLEFT:

				$ret[] = GD_TEMPLATE_TAGSOCIALMEDIA;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_TAGRIGHT:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_TAGINFO;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_TagMultipleSidebarComponents();