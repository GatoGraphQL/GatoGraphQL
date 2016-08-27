<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC', PoP_ServerUtils::get_template_definition('horizontal-sidebarinner-author-generic'));

class GD_Template_Processor_CustomHorizontalAuthorSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:

				$ret = array_merge(
					$ret,
					FullUserSidebarSettings::get_components(GD_SIDEBARSECTION_GENERIC)
				);
				break;
		}

		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
			
				return 'col-xsm-4';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomHorizontalAuthorSidebarInners();