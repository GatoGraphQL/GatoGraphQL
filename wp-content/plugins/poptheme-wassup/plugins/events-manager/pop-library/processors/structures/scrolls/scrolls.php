<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_LOCATIONS', PoP_ServerUtils::get_template_definition('scroll-locations'));

define ('GD_TEMPLATE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scroll-myevents-simpleviewpreview'));
define ('GD_TEMPLATE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scroll-mypastevents-simpleviewpreview'));

define ('GD_TEMPLATE_SCROLL_MYEVENTS_FULLVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scroll-myevents-fullviewpreview'));
define ('GD_TEMPLATE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW', PoP_ServerUtils::get_template_definition('scroll-mypastevents-fullviewpreview'));

define ('GD_TEMPLATE_SCROLL_EVENTS_NAVIGATOR', PoP_ServerUtils::get_template_definition('scroll-events-navigator'));
define ('GD_TEMPLATE_SCROLL_PASTEVENTS_NAVIGATOR', PoP_ServerUtils::get_template_definition('scroll-pastevents-navigator'));

define ('GD_TEMPLATE_SCROLL_EVENTS_ADDONS', PoP_ServerUtils::get_template_definition('scroll-events-addons'));
define ('GD_TEMPLATE_SCROLL_PASTEVENTS_ADDONS', PoP_ServerUtils::get_template_definition('scroll-pastevents-addons'));

define ('GD_TEMPLATE_SCROLL_EVENTS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-events-details'));
define ('GD_TEMPLATE_SCROLL_PASTEVENTS_DETAILS', PoP_ServerUtils::get_template_definition('scroll-pastevents-details'));

define ('GD_TEMPLATE_SCROLL_EVENTS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scroll-events-simpleview'));
define ('GD_TEMPLATE_SCROLL_PASTEVENTS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scroll-pastevents-simpleview'));

define ('GD_TEMPLATE_SCROLL_EVENTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-events-fullview'));
define ('GD_TEMPLATE_SCROLL_PASTEVENTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-pastevents-fullview'));

// define ('GD_TEMPLATE_SCROLL_AUTHOREVENTS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scroll-authorevents-simpleview'));
// define ('GD_TEMPLATE_SCROLL_AUTHORPASTEVENTS_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('scroll-authorpastevents-simpleview'));

define ('GD_TEMPLATE_SCROLL_AUTHOREVENTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-authorevents-fullview'));
define ('GD_TEMPLATE_SCROLL_AUTHORPASTEVENTS_FULLVIEW', PoP_ServerUtils::get_template_definition('scroll-authorpastevents-fullview'));

define ('GD_TEMPLATE_SCROLL_EVENTS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-events-thumbnail'));
define ('GD_TEMPLATE_SCROLL_PASTEVENTS_THUMBNAIL', PoP_ServerUtils::get_template_definition('scroll-pastevents-thumbnail'));

define ('GD_TEMPLATE_SCROLL_EVENTS_LIST', PoP_ServerUtils::get_template_definition('scroll-events-list'));
define ('GD_TEMPLATE_SCROLL_PASTEVENTS_LIST', PoP_ServerUtils::get_template_definition('scroll-pastevents-list'));

class GD_EM_Template_Processor_CustomScrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_LOCATIONS,
			GD_TEMPLATE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYEVENTS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_EVENTS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_PASTEVENTS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_EVENTS_ADDONS,
			GD_TEMPLATE_SCROLL_PASTEVENTS_ADDONS,
			GD_TEMPLATE_SCROLL_EVENTS_DETAILS,
			GD_TEMPLATE_SCROLL_FEATURED_DETAILS,
			GD_TEMPLATE_SCROLL_PASTEVENTS_DETAILS,
			GD_TEMPLATE_SCROLL_EVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_PASTEVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_EVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_PASTEVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_EVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_PASTEVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_EVENTS_LIST,
			GD_TEMPLATE_SCROLL_PASTEVENTS_LIST,
			// GD_TEMPLATE_SCROLL_AUTHOREVENTS_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLL_AUTHORPASTEVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_AUTHOREVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTHORPASTEVENTS_FULLVIEW,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_LOCATIONS => GD_TEMPLATE_SCROLLINNER_LOCATIONS,
			GD_TEMPLATE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW => GD_TEMPLATE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW => GD_TEMPLATE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYEVENTS_FULLVIEWPREVIEW => GD_TEMPLATE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW => GD_TEMPLATE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_EVENTS_NAVIGATOR => GD_TEMPLATE_SCROLLINNER_EVENTS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_PASTEVENTS_NAVIGATOR => GD_TEMPLATE_SCROLLINNER_PASTEVENTS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_EVENTS_ADDONS => GD_TEMPLATE_SCROLLINNER_EVENTS_ADDONS,
			GD_TEMPLATE_SCROLL_PASTEVENTS_ADDONS => GD_TEMPLATE_SCROLLINNER_PASTEVENTS_ADDONS,
			GD_TEMPLATE_SCROLL_EVENTS_DETAILS => GD_TEMPLATE_SCROLLINNER_EVENTS_DETAILS,
			GD_TEMPLATE_SCROLL_PASTEVENTS_DETAILS => GD_TEMPLATE_SCROLLINNER_PASTEVENTS_DETAILS,
			GD_TEMPLATE_SCROLL_EVENTS_SIMPLEVIEW => GD_TEMPLATE_SCROLLINNER_EVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_PASTEVENTS_SIMPLEVIEW => GD_TEMPLATE_SCROLLINNER_PASTEVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_EVENTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_EVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_PASTEVENTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_PASTEVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_EVENTS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_EVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_PASTEVENTS_THUMBNAIL => GD_TEMPLATE_SCROLLINNER_PASTEVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_EVENTS_LIST => GD_TEMPLATE_SCROLLINNER_EVENTS_LIST,
			GD_TEMPLATE_SCROLL_PASTEVENTS_LIST => GD_TEMPLATE_SCROLLINNER_PASTEVENTS_LIST,
			// GD_TEMPLATE_SCROLL_AUTHOREVENTS_SIMPLEVIEW => GD_TEMPLATE_SCROLLINNER_AUTHOREVENTS_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLL_AUTHORPASTEVENTS_SIMPLEVIEW => GD_TEMPLATE_SCROLLINNER_AUTHORPASTEVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_AUTHOREVENTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTHOREVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTHORPASTEVENTS_FULLVIEW => GD_TEMPLATE_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
			
		// Extra classes
		$thumbnails = array(
			GD_TEMPLATE_SCROLL_PASTEVENTS_THUMBNAIL,
			GD_TEMPLATE_SCROLL_EVENTS_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_SCROLL_PASTEVENTS_LIST,
			GD_TEMPLATE_SCROLL_EVENTS_LIST,
		);
		$details = array(
			GD_TEMPLATE_SCROLL_PASTEVENTS_DETAILS,
			GD_TEMPLATE_SCROLL_EVENTS_DETAILS,
		);
		$navigators = array(
			GD_TEMPLATE_SCROLL_EVENTS_NAVIGATOR,
			GD_TEMPLATE_SCROLL_PASTEVENTS_NAVIGATOR,
		);
		$addons = array(
			GD_TEMPLATE_SCROLL_EVENTS_ADDONS,
			GD_TEMPLATE_SCROLL_PASTEVENTS_ADDONS,
		);
		$simpleviews = array(
			GD_TEMPLATE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_EVENTS_SIMPLEVIEW,
			GD_TEMPLATE_SCROLL_PASTEVENTS_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLL_AUTHOREVENTS_SIMPLEVIEW,
			// GD_TEMPLATE_SCROLL_AUTHORPASTEVENTS_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_SCROLL_MYEVENTS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW,
			GD_TEMPLATE_SCROLL_EVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_PASTEVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTHOREVENTS_FULLVIEW,
			GD_TEMPLATE_SCROLL_AUTHORPASTEVENTS_FULLVIEW,
		);

		$extra_class = '';
		if (in_array($template_id, $navigators)) {
			$extra_class = 'navigator text-inverse';
		}
		elseif (in_array($template_id, $addons)) {
			$extra_class = 'addons';
		}
		elseif (in_array($template_id, $simpleviews)) {
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


		$inner = $this->get_inner_template($template_id);
		if (in_array($template_id, $navigators)) {

			// Make it activeItem: highlight on viewing the corresponding fullview
			$this->append_att($inner, $atts, 'class', 'pop-activeitem');
		}
		// elseif (in_array($template_id, $fullviews)) {

			// For the waypoint, save the original URL before it's changed, and change it back when scrolling up the page
			// $this->append_att($template_id, $atts, 'class', 'waypoint template-historystate original');
			// $this->merge_block_jsmethod_att($template_id, $atts, array('waypointsHistoryStateOriginal'));
		// }
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomScrolls();