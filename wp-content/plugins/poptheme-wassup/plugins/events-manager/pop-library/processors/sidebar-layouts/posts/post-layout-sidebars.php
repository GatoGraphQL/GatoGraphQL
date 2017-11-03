<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-vertical-event'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-vertical-pastevent'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-horizontal-event'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-horizontal-pastevent'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarcompact-horizontal-event'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarcompact-horizontal-pastevent'));

class GD_EM_Template_Processor_CustomPostLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT,
			
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_EVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_PASTEVENT:

				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT:

				$this->append_att($template_id, $atts, 'class', 'horizontal');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomPostLayoutSidebars();