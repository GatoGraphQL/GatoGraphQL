<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS', PoP_ServerUtils::get_template_definition('scrollinner-automatedemails-notifications-details'));
define ('GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST', PoP_ServerUtils::get_template_definition('scrollinner-automatedemails-notifications-list'));

class GD_AAL_Template_Processor_AutomatedEmailsScrollInners extends GD_Template_Processor_ScrollInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS:
			case GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST:
			
				return array(
					'row-items' => 1, 
					'class' => 'col-sm-12'
				);
		}

		return parent::get_layout_grid($template_id, $atts);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_DETAILS,
			GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST => GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_LIST,
		);
		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_AutomatedEmailsScrollInners();