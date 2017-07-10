<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT', PoP_ServerUtils::get_template_definition('layout-automatedemails-fullview-event'));
// define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT', PoP_ServerUtils::get_template_definition('layout-automatedemails-fullview-pastevent'));
// define ('GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT', PoP_ServerUtils::get_template_definition('authorlayout-automatedemails-fullview-event'));
// define ('GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT', PoP_ServerUtils::get_template_definition('authorlayout-automatedemails-fullview-pastevent'));
// define ('GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT', PoP_ServerUtils::get_template_definition('singlelayout-automatedemails-fullview-event'));
// define ('GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT', PoP_ServerUtils::get_template_definition('singlelayout-automatedemails-fullview-pastevent'));

class PoP_ThemeWassup_EM_AE_Template_Processor_FullViewLayouts extends GD_Template_Processor_CustomFullViewLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,
			// GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT,			
			// GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,
			// GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT,			
			// GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT,
			// GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT,
		);
	}

	function get_footer_templates($template_id) {

		$ret = parent::get_footer_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT:
			// case GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT:
			// case GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT:
			// case GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT:
			// case GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT:

				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_EVENTBOTTOM;
				break;
		}

		return $ret;
	}

	function get_sidebar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT:
			// case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT:
			
			// case GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT:
			// case GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT:
			
			// case GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT:
			// case GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT:

				$sidebars = array(
					GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT,
					// GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT,
					
					// GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT,
					// GD_TEMPLATE_AUTHORLAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT,
					
					// GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT,
					// GD_TEMPLATE_SINGLELAYOUT_AUTOMATEDEMAILS_FULLVIEW_PASTEVENT => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT,
				);

				return $sidebars[$template_id];
		}

		return parent::get_sidebar_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_EM_AE_Template_Processor_FullViewLayouts();