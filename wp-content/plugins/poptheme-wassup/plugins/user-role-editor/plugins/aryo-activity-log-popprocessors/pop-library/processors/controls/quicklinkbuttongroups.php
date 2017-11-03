<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('ure-aal-quicklinkbuttongroup-editusermembership'));
define ('GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS', PoP_TemplateIDUtils::get_template_definition('ure-aal-quicklinkbuttongroup-viewallmembers'));

class GD_URE_AAL_Template_Processor_QuicklinkButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP,
			GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP:

				$ret[] = GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_EDITMEMBERSHIP; //GD_URE_AAL_TEMPLATE_BUTTON_EDITMEMBERSHIP
				break;

			case GD_URE_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS:

				$ret[] = GD_URE_AAL_TEMPLATE_BUTTONWRAPPER_VIEWALLMEMBERS; //GD_URE_AAL_TEMPLATE_BUTTON_VIEWALLMEMBERS
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_AAL_Template_Processor_QuicklinkButtonGroups();