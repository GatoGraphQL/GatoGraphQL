<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_NOTIFICATIONS_DETAILS', PoP_TemplateIDUtils::get_template_definition('scroll-notifications-details'));
define ('GD_TEMPLATE_SCROLL_NOTIFICATIONS_LIST', PoP_TemplateIDUtils::get_template_definition('scroll-notifications-list'));

class GD_AAL_Template_Processor_CustomScrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_NOTIFICATIONS_DETAILS,
			GD_TEMPLATE_SCROLL_NOTIFICATIONS_LIST,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_NOTIFICATIONS_DETAILS => GD_TEMPLATE_SCROLLINNER_NOTIFICATIONS_DETAILS,
			GD_TEMPLATE_SCROLL_NOTIFICATIONS_LIST => GD_TEMPLATE_SCROLLINNER_NOTIFICATIONS_LIST,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
			
		// Extra classes
		$lists = array(
			GD_TEMPLATE_SCROLL_NOTIFICATIONS_LIST,
		);
		$details = array(
			GD_TEMPLATE_SCROLL_NOTIFICATIONS_DETAILS,
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

			case GD_TEMPLATE_SCROLL_NOTIFICATIONS_DETAILS:
			case GD_TEMPLATE_SCROLL_NOTIFICATIONS_LIST:
			
				// Artificial property added to identify the template when adding template-resources
				$this->add_att($template_id, $atts, 'resourceloader', 'scroll-notifications');
				$this->append_att($template_id, $atts, 'class', 'scroll-notifications');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_CustomScrolls();