<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-vertical'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_LINK', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-vertical-link'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-vertical-highlight'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_WEBPOST', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-vertical-webpost'));

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-horizontal'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-horizontal-link'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-horizontal-highlight'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_WEBPOST', PoP_TemplateIDUtils::get_template_definition('layout-postsidebar-horizontal-webpost'));

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarcompact-horizontal'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarcompact-horizontal-link'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarcompact-horizontal-highlight'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarcompact-horizontal-webpost'));

class GD_Template_Processor_CustomPostLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_WEBPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_WEBPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST,
			
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_LINK => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_WEBPOST => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_WEBPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_WEBPOST => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_WEBPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST => GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_WEBPOST,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_LINK:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_VERTICAL_WEBPOST:

				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_LINK:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_WEBPOST:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST:

				$this->append_att($template_id, $atts, 'class', 'horizontal');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPostLayoutSidebars();