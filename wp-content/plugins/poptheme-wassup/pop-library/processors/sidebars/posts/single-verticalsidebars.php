<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_LINK', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-link'));
define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-highlight'));
define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_WEBPOST', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-webpost'));

class Wassup_Template_Processor_CustomVerticalSingleSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_LINK,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_WEBPOST,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_LINK => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_LINK,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_WEBPOST => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_WEBPOST,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_LINK:
			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_HIGHLIGHT:
			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_WEBPOST:
			
				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_CustomVerticalSingleSidebars();