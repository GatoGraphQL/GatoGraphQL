<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_GENERIC', PoP_TemplateIDUtils::get_template_definition('horizontal-sidebar-author-generic'));

// class GD_Template_Processor_CustomHorizontalAuthorSidebars extends GD_Template_Processor_HorizontalContentSidebarsBase {
class GD_Template_Processor_CustomHorizontalAuthorSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_GENERIC,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_GENERIC => GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomHorizontalAuthorSidebars();