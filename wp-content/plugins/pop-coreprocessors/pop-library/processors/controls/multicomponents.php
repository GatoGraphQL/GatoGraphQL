<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_ANCHORCONTROL_TOGGLETABS', PoP_ServerUtils::get_template_definition('multicomponent-anchorcontrol-toggletabs'));

class GD_Template_Processor_ControlMulticomponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_ANCHORCONTROL_TOGGLETABS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_ANCHORCONTROL_TOGGLETABS:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLETABS;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLETABSXS;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ControlMulticomponents();