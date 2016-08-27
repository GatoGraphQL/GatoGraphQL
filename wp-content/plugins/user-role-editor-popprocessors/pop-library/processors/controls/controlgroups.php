<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_CONTROLGROUP_CONTENTSOURCE', PoP_ServerUtils::get_template_definition('ure-controlgroup-contentsource'));

class GD_URE_Template_Processor_ControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_CONTROLGROUP_CONTENTSOURCE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		global $gd_template_processor_manager;

		switch ($template_id) {
				
			case GD_URE_TEMPLATE_CONTROLGROUP_CONTENTSOURCE:

				$ret[] = GD_URE_TEMPLATE_CONTROLBUTTONGROUP_CONTENTSOURCE;
				// $ret[] = GD_URE_TEMPLATE_DROPDOWNBUTTONCONTROL_CONTENTSOURCE;
				break;
		}

		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	switch ($template_id) {
					
	// 		case GD_URE_TEMPLATE_CONTROLGROUP_CONTENTSOURCE:
				
	// 			$this->append_att(GD_URE_TEMPLATE_CONTROLBUTTONGROUP_CONTENTSOURCE, $atts, 'class', 'hidden-xs');
	// 			$this->append_att(GD_URE_TEMPLATE_DROPDOWNBUTTONCONTROL_CONTENTSOURCE, $atts, 'class', 'hidden-sm hidden-md hidden-lg');
	// 			break;
	// 	}
		
	// 	return parent::init_atts($template_id, $atts);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ControlGroups();