<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_QUICKLINKGROUP_EVENTBOTTOM', PoP_TemplateIDUtils::get_template_definition('quicklinkgroup-automatedemails-eventbottom'));

class PoP_ThemeWassup_EM_AE_Template_Processor_QuicklinkGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_QUICKLINKGROUP_EVENTBOTTOM,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {
								
			case GD_TEMPLATE_QUICKLINKGROUP_EVENTBOTTOM:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL;
				$ret[] = GD_EM_TEMPLATE_QUICKLINKBUTTONGROUP_DOWNLOADLINKS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_EM_AE_Template_Processor_QuicklinkGroups();