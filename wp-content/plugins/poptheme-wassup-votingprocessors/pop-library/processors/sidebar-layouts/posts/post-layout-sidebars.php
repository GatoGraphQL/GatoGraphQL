<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('layout-postsidebar-vertical-opinionatedvote'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('layout-postsidebar-horizontal-opinionatedvote'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('layout-postsidebarcompact-horizontal-opinionatedvote'));

class VotingProcessors_Template_Processor_CustomPostLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_OPINIONATEDVOTE,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_OPINIONATEDVOTE,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_OPINIONATEDVOTE,
			
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_OPINIONATEDVOTE => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_OPINIONATEDVOTE,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_OPINIONATEDVOTE => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_OPINIONATEDVOTE,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_OPINIONATEDVOTE => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_OPINIONATEDVOTE,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_OPINIONATEDVOTE:

				$this->append_att($template_id, $atts, 'class', 'vertical opinionatedvotes');
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_OPINIONATEDVOTE:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_OPINIONATEDVOTE:

				$this->append_att($template_id, $atts, 'class', 'horizontal opinionatedvotes');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomPostLayoutSidebars();