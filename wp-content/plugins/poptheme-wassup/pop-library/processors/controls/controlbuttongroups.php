<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ', PoP_ServerUtils::get_template_definition('customcontrolbuttongroup-addcontentfaq'));
define ('GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ', PoP_ServerUtils::get_template_definition('customcontrolbuttongroup-accountfaq'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_ADDWEBPOST', PoP_ServerUtils::get_template_definition('controlbuttongroup-addwebpost'));

class GD_Template_Processor_CustomControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ,
			GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ,
			GD_TEMPLATE_CONTROLBUTTONGROUP_ADDWEBPOST,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ:

				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ;
				break;

			case GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ:

				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_ACCOUNTFAQ;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_ADDWEBPOST:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOST;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_ADDWEBPOSTLINK;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomControlButtonGroups();