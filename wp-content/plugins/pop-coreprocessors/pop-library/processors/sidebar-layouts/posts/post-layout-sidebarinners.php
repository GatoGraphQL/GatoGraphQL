<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL', PoP_ServerUtils::get_template_definition('layout-postconclusionsidebarinner-horizontal'));
define ('GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL', PoP_ServerUtils::get_template_definition('layout-subjugatedpostconclusionsidebarinner-horizontal'));

class GD_Template_Processor_PostLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL:

				$ret[] = GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT;
				$ret[] = GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT;
				break;

			case GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL:

				$ret[] = GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT;
				$ret[] = GD_TEMPLATE_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT;
				break;

		}
		
		return $ret;
	}

	// function get_wrapper_class($template_id) {

	// 	switch ($template_id) {
			
	// 		case GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
	// 		case GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL:

	// 			return 'row';
	// 	}
	
	// 	return parent::get_wrapper_class($template_id);
	// }
	
	// function get_widgetwrapper_class($template_id) {

	// 	switch ($template_id) {
			
	// 		case GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
	// 		case GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
			
	// 			return 'col-xsm-6';
	// 	}
	
	// 	return parent::get_widgetwrapper_class($template_id);
	// }

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
			case GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBARINNER_HORIZONTAL:
			
				$this->append_att(GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT, $atts, 'class', 'pull-right');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostLayoutSidebarInners();