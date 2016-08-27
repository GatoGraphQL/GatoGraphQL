<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_ORGANIZATION', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-vertical-organization'));
define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_INDIVIDUAL', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-vertical-individual'));

define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-horizontal-organization'));
define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-horizontal-individual'));

define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-compacthorizontal-organization'));
define ('GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL', PoP_ServerUtils::get_template_definition('layout-usersidebarinner-compacthorizontal-individual'));

// class GD_Template_Processor_CustomUserLayoutSidebarInners extends GD_Template_Processor_LayoutSidebarInnersBase {
class GD_URE_Template_Processor_CustomUserLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_INDIVIDUAL,
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL,
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION,
			GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_ORGANIZATION:

				$ret = array_merge(
					$ret,
					URE_FullUserSidebarSettings::get_components(GD_SIDEBARSECTION_ORGANIZATION)
				);
				break;

			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_VERTICAL_INDIVIDUAL:

				$ret = array_merge(
					$ret,
					URE_FullUserSidebarSettings::get_components(GD_SIDEBARSECTION_INDIVIDUAL)
				);
				break;

			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION:

				$ret = array_merge(
					$ret,
					URE_FullUserSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_ORGANIZATION)
				);
				break;

			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL:

				$ret = array_merge(
					$ret,
					URE_FullUserSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_INDIVIDUAL)
				);
				break;
		}
		
		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL:
			
				return 'col-xsm-4';

			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION:
			case GD_TEMPLATE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL:

				return 'col-xsm-6';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomUserLayoutSidebarInners();