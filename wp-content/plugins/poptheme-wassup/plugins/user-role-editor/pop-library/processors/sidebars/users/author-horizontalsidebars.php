<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION', PoP_TemplateIDUtils::get_template_definition('horizontal-sidebar-author-organization'));
define ('GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL', PoP_TemplateIDUtils::get_template_definition('horizontal-sidebar-author-individual'));

class GD_URE_Template_Processor_CustomHorizontalAuthorSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION,
			GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION => GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION,
			GD_TEMPLATE_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL => GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL,
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
new GD_URE_Template_Processor_CustomHorizontalAuthorSidebars();