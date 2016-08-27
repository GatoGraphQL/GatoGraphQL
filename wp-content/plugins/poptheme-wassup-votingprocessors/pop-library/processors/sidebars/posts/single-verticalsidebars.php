<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('vertical-sidebar-single-opinionatedvote'));

class VotingProcessors_Template_Processor_CustomVerticalSingleSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_OPINIONATEDVOTE,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_OPINIONATEDVOTE => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_OPINIONATEDVOTE,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_OPINIONATEDVOTE:
			
				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomVerticalSingleSidebars();