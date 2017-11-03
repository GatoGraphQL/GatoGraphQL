<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layout-postconclusionsidebar-horizontal'));
define ('GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layout-subjugatedpostconclusionsidebar-horizontal'));

class GD_Template_Processor_PostLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
			
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL => GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL => GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	// function init_atts($template_id, &$atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
	// 		case GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:

	// 			$this->append_att($template_id, $atts, 'class', 'horizontal conclusion pop-hidden-print');
	// 			break;
	// 	}

	// 	return parent::init_atts($template_id, $atts);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostLayoutSidebars();