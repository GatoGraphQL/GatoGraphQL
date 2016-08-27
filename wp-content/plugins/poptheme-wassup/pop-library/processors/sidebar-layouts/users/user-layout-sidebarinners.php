<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_GENERIC', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-vertical-generic'));

define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_GENERIC', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-horizontal-generic'));

define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_GENERIC', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-compacthorizontal-generic'));

// class GD_Template_Processor_CustomUserLayoutSidebarInners extends GD_Template_Processor_LayoutSidebarInnersBase {
class GD_Template_Processor_CustomUserLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_GENERIC,
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_GENERIC,
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_GENERIC,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_GENERIC:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_GENERIC:

				$ret = array_merge(
					$ret,
					FullUserSidebarSettings::get_components(GD_SIDEBARSECTION_GENERIC)
				);
				break;

			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_GENERIC:

				$ret = array_merge(
					$ret,
					FullUserSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_GENERIC)
				);
				break;
		}
		
		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_GENERIC:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_GENERIC:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_GENERIC:
			
				return 'col-xsm-4';

			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_GENERIC:

				return 'col-xsm-6';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomUserLayoutSidebarInners();