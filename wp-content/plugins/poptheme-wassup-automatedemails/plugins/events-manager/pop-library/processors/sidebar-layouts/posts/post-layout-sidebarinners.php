<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-postsidebarinner-compacthorizontal-event'));
// define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-postsidebarinner-compacthorizontal-pastevent'));

class PoP_ThemeWassup_EM_AE_Template_Processor_CustomPostLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT,
			// GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:

				$ret = array_merge(
					$ret,
					EM_AE_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_EVENT)
				);
				break;

			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:

			// 	$ret = array_merge(
			// 		$ret,
			// 		EM_AE_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_AUTOMATEDEMAILS_PASTEVENT)
			// 	);
			// 	break;
		}
		
		return $ret;
	}

	// Comment Leo 12/07/2017: these 2 functions are commented, since class="row" doesn't work in emails
	// function get_wrapper_class($template_id) {

	// 	switch ($template_id) {
			
	// 		case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
	// 		// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:

	// 			return 'row';
	// 	}
	
	// 	return parent::get_wrapper_class($template_id);
	// }
	
	// function get_widgetwrapper_class($template_id) {

	// 	switch ($template_id) {
			
	// 		case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
	// 		// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:
			
	// 			return 'col-xsm-6';
	// 	}
	
	// 	return parent::get_widgetwrapper_class($template_id);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_EM_AE_Template_Processor_CustomPostLayoutSidebarInners();