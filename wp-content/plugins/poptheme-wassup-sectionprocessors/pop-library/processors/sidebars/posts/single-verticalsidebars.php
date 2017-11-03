<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_LOCATIONPOST', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-locationpost'));
define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_STORY', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-story'));
define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_DISCUSSION', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-discussion'));
define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_BLOG', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-blog'));
define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_ANNOUNCEMENT', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-announcement'));
define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_FEATURED', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-single-featured'));

class GD_Template_Processor_CustomVerticalSingleSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_LOCATIONPOST,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_STORY,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_DISCUSSION,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_BLOG,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_ANNOUNCEMENT,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_FEATURED,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_LOCATIONPOST => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_STORY => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_STORY,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_DISCUSSION => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_DISCUSSION,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_BLOG => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_BLOG,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_ANNOUNCEMENT => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_ANNOUNCEMENT,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_FEATURED => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_FEATURED,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_LOCATIONPOST:
			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_STORY:
			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_DISCUSSION:
			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_BLOG:
			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_ANNOUNCEMENT:
			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_FEATURED:
			
				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomVerticalSingleSidebars();