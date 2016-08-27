<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLBUTTONGROUP_INVITENEWMEMBERS', PoP_ServerUtils::get_template_definition('controlbuttongroup-invitenewmembers'));

class GD_URE_Template_Processor_CustomControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLBUTTONGROUP_INVITENEWMEMBERS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_CONTROLBUTTONGROUP_INVITENEWMEMBERS:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomControlButtonGroups();