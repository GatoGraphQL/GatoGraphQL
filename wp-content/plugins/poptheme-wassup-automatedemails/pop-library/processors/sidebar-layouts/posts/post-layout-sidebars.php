<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-postsidebarcompact-horizontal-post'));

class PoPTheme_Wassup_AE_Template_Processor_CustomPostLayoutSidebars extends GD_Template_Processor_SidebarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST,
			
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST:

				$this->append_att($template_id, $atts, 'class', 'horizontal');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_CustomPostLayoutSidebars();