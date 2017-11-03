<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_TAGSIDEBAR_VERTICAL', PoP_TemplateIDUtils::get_template_definition('layout-tagsidebar-vertical'));

define ('GD_TEMPLATE_LAYOUT_TAGSIDEBAR_HORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layout-tagsidebar-horizontal'));

define ('GD_TEMPLATE_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layout-tagsidebar-compacthorizontal'));

class GD_Template_Processor_CustomTagLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_TAGSIDEBAR_VERTICAL,
			GD_TEMPLATE_LAYOUT_TAGSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_TAGSIDEBAR_VERTICAL => GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_VERTICAL,
			GD_TEMPLATE_LAYOUT_TAGSIDEBAR_HORIZONTAL => GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL => GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_TAGSIDEBAR_VERTICAL:

				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;

			case GD_TEMPLATE_LAYOUT_TAGSIDEBAR_HORIZONTAL:
			case GD_TEMPLATE_LAYOUT_TAGSIDEBAR_COMPACTHORIZONTAL:

				$this->append_att($template_id, $atts, 'class', 'horizontal');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomTagLayoutSidebars();