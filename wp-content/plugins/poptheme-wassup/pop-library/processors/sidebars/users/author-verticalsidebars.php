<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBAR_AUTHOR_GENERIC', PoP_TemplateIDUtils::get_template_definition('vertical-sidebar-author-generic'));

class GD_Template_Processor_CustomVerticalAuthorSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBAR_AUTHOR_GENERIC,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_VERTICALSIDEBAR_AUTHOR_GENERIC => GD_TEMPLATE_VERTICALSIDEBARINNER_AUTHOR_GENERIC,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBAR_AUTHOR_GENERIC:

				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomVerticalAuthorSidebars();