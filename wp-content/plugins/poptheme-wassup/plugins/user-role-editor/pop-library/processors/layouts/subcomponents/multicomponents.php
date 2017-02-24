<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_MULTICOMPONENT_ORGANIZATIONMEMBERS', PoP_ServerUtils::get_template_definition('ure-multicomponent-organizationmembers'));

class GD_URE_Template_Processor_MembersLayoutMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_MULTICOMPONENT_ORGANIZATIONMEMBERS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_URE_TEMPLATE_MULTICOMPONENT_ORGANIZATIONMEMBERS:

				$ret[] = GD_URE_TEMPLATE_LAYOUT_ORGANIZATIONMEMBERS;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_MULTICOMPONENT_ORGANIZATIONMEMBERS:

				$this->append_att($template_id, $atts, 'class', 'clearfix');
				$this->append_att(GD_URE_TEMPLATE_LAYOUT_ORGANIZATIONMEMBERS, $atts, 'class', 'pull-left');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_MembersLayoutMultipleComponents();