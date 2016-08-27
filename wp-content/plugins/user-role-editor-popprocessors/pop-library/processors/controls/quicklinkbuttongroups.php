<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP', PoP_ServerUtils::get_template_definition('ure-quicklinkbuttongroup-user-editmembership'));

class GD_URE_Template_Processor_QuicklinkButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP:

				$ret[] = GD_URE_TEMPLATE_BUTTON_EDITMEMBERSHIP;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_QuicklinkButtonGroups();