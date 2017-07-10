<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-automatedemails-events-details'));
// define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-automatedemails-pastevents-details'));

define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scroll-automatedemails-events-simpleview'));
// define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scroll-automatedemails-pastevents-simpleview'));

define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-automatedemails-events-fullview'));
// define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-automatedemails-pastevents-fullview'));

// define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-automatedemails-authorevents-fullview'));
// define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-automatedemails-authorpastevents-fullview'));

define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-automatedemails-events-thumbnail'));
// define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-automatedemails-pastevents-thumbnail'));

define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST', PoP_ServerUtils::get_template_definition('scroll-automatedemails-events-list'));
// define ('GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_LIST', PoP_ServerUtils::get_template_definition('scroll-automatedemails-pastevents-list'));

class PoP_ThemeWassup_EM_AE_Template_Processor_Scrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_LIST,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_DETAILS => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_LIST => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_PASTEVENTS_LIST,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
			
		// Extra classes
		$thumbnails = array(
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL,
		);
		$lists = array(
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_LIST,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST,
		);
		$details = array(
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_DETAILS,
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_PASTEVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_AUTHOREVENTS_FULLVIEW,
			// GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_AUTHORPASTEVENTS_FULLVIEW,
		);

		$extra_class = '';
		if (in_array($template_id, $simpleviews)) {
			$extra_class = 'simpleview';
		}
		elseif (in_array($template_id, $fullviews)) {
			$extra_class = 'fullview';
		}
		elseif (in_array($template_id, $details)) {
			$extra_class = 'details';
		}
		elseif (in_array($template_id, $thumbnails)) {
			$extra_class = 'thumb';
		}
		elseif (in_array($template_id, $lists)) {
			$extra_class = 'list';
		}
		$this->append_att($template_id, $atts, 'class', $extra_class);

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_EM_AE_Template_Processor_Scrolls();