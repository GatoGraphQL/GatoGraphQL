<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AutomatedEmailsPreviewNotificationLayoutsBase extends GD_Template_Processor_PreviewNotificationLayoutsBase {

	function get_quicklinkgroup_top($template_id) {

		return null;
	}
	function get_link_template($template_id) {

		return null;
	}
	function add_url_link($template_id, $atts) {

		return true;
	}
}