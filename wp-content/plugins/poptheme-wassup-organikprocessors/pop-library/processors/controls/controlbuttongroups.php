<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLBUTTONGROUP_ADDFARM', PoP_ServerUtils::get_template_definition('customcontrolbuttongroup-addfarm'));

class OrganikProcessors_Template_Processor_ControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLBUTTONGROUP_ADDFARM,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_ADDFARM:

				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARM;
				$ret[] = GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDFARMLINK;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OrganikProcessors_Template_Processor_ControlButtonGroups();