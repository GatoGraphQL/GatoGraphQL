<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-automatedemails-notifications-details'));
define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST', PoP_ServerUtils::get_template_definition('scroll-automatedemails-notifications-list'));

class GD_AAL_Template_Processor_AutomatedEmailsScrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
			
		// Extra classes
		$lists = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST,
		);
		$details = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS,
		);

		$extra_class = '';
		if (in_array($template_id, $details)) {
			$extra_class = 'details';
		}
		elseif (in_array($template_id, $lists)) {
			$extra_class = 'list';
		}
		$this->append_att($template_id, $atts, 'class', $extra_class);

		switch ($template_id) {

			case GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS:
			case GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST:
			
				$this->append_att($template_id, $atts, 'class', 'scroll-notifications');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_AutomatedEmailsScrolls();