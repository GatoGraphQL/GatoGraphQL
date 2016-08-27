<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_FARM', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-vertical-farm'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FARM', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-horizontal-farm'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FARM', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-farm'));

class OP_Template_Processor_CustomPostLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_FARM,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FARM,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FARM,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_FARM:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FARM:

				$ret = array_merge(
					$ret,
					OP_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_FARM)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FARM:

				$ret = array_merge(
					$ret,
					OP_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_FARM)
				);
				break;
		}
		
		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FARM:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FARM:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FARM:
			
				return 'col-xsm-4';
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FARM:
			
				return 'col-xsm-6';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomPostLayoutSidebarInners();