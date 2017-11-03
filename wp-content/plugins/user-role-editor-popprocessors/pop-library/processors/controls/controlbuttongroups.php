<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_CONTROLBUTTONGROUP_CONTENTSOURCE', PoP_TemplateIDUtils::get_template_definition('ure-controlbuttongroup-contentsource'));

class GD_URE_Template_Processor_ControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_CONTROLBUTTONGROUP_CONTENTSOURCE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_URE_TEMPLATE_CONTROLBUTTONGROUP_CONTENTSOURCE:

				$ret[] = GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY;
				$ret[] = GD_URE_TEMPLATE_ANCHORCONTROL_CONTENTSOURCEORGANIZATION;
				break;
		}
		
		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	switch ($template_id) {
					
	// 		case GD_URE_TEMPLATE_CONTROLBUTTONGROUP_CONTENTSOURCE:
				
	// 			$this->append_att($template_id, $atts, 'class', 'btn-group-justified');
	// 			break;
	// 	}
		
	// 	return parent::init_atts($template_id, $atts);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ControlButtonGroups();