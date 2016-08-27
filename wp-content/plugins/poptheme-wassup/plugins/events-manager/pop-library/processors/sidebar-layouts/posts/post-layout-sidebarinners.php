<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-vertical-event'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-vertical-pastevent'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-horizontal-event'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-horizontal-pastevent'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-event'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-pastevent'));

class GD_EM_Template_Processor_CustomPostLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:

				$ret = array_merge(
					$ret,
					EM_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_EVENT)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:

				$ret = array_merge(
					$ret,
					EM_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_PASTEVENT)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:

				$ret = array_merge(
					$ret,
					EM_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_EVENT)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:

				$ret = array_merge(
					$ret,
					EM_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_PASTEVENT)
				);
				break;

		}
		
		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:
			
				return 'col-xsm-4';
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:
			
				return 'col-xsm-6';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomPostLayoutSidebarInners();