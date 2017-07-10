<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST', PoP_ServerUtils::get_template_definition('layout-automatedemails-fullview-post'));

class PoPTheme_Wassup_AE_Template_Processor_FullViewLayouts extends GD_Template_Processor_CustomFullViewLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST,
		);
	}

	function get_footer_templates($template_id) {

		$ret = parent::get_footer_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL;
				break;
		}

		return $ret;
	}

	function get_sidebar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST:

				$sidebars = array(
					GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST,
				);

				return $sidebars[$template_id];
		}

		return parent::get_sidebar_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_FullViewLayouts();