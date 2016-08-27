<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_FARM', PoP_ServerUtils::get_template_definition('layout-postsidebar-vertical-farm'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_FARM', PoP_ServerUtils::get_template_definition('layout-postsidebar-horizontal-farm'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FARM', PoP_ServerUtils::get_template_definition('layout-postsidebarcompact-horizontal-farm'));

class OP_Template_Processor_CustomPostLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_FARM,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_FARM,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FARM,
			
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_FARM => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_FARM,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_FARM => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FARM,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FARM => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FARM,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_FARM:

				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_FARM:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FARM:

				$this->append_att($template_id, $atts, 'class', 'horizontal');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomPostLayoutSidebars();