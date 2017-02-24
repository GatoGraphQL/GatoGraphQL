<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUT_ORGANIZATIONMEMBERS', PoP_ServerUtils::get_template_definition('ure-layout-organizationmembers'));

class GD_URE_Template_Processor_MembersLayouts extends GD_URE_Template_Processor_MembersLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUT_ORGANIZATIONMEMBERS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		$layouts = array(
			GD_URE_TEMPLATE_LAYOUT_ORGANIZATIONMEMBERS => GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR60,
		);
		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}

	// function get_html_tag($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_URE_TEMPLATE_LAYOUT_ORGANIZATIONMEMBERS:
				
	// 			return 'span';
	// 	}
	
	// 	return parent::get_html_tag($template_id, $atts);
	// }

	// function init_atts($template_id, &$atts) {

	// 	switch ($template_id) {

	// 		case GD_URE_TEMPLATE_LAYOUT_ORGANIZATIONMEMBERS:

	// 			$this->append_att($template_id, $atts, 'class', 'pull-left');
	// 			break;
	// 	}

	// 	return parent::init_atts($template_id, $atts);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_MembersLayouts();