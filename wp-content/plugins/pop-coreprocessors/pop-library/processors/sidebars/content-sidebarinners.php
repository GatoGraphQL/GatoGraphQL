<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_CONTENT_HORIZONTAL', PoP_ServerUtils::get_template_definition('contentsidebarinner-horizontal'));
define ('GD_TEMPLATE_SIDEBARINNER_CONTENT_VERTICAL', PoP_ServerUtils::get_template_definition('contentsidebarinner-vertical'));

class GD_Template_Processor_ContentSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_CONTENT_HORIZONTAL,
			GD_TEMPLATE_SIDEBARINNER_CONTENT_VERTICAL,
		);
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_SIDEBARINNER_CONTENT_HORIZONTAL:
				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}

	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_SIDEBARINNER_CONTENT_HORIZONTAL:
				return 'col-xsm-4';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ContentSidebarInners();