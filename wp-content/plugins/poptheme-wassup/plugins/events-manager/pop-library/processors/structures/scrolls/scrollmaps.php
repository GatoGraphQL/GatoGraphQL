<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_ALLUSERS_MAP', PoP_ServerUtils::get_template_definition('scroll-allusers-map'));
define ('GD_TEMPLATE_SCROLL_SEARCHUSERS_MAP', PoP_ServerUtils::get_template_definition('scroll-searchusers-map'));
define ('GD_TEMPLATE_SCROLL_AUTHORFOLLOWERS_MAP', PoP_ServerUtils::get_template_definition('scroll-authorfollowers-map'));
define ('GD_TEMPLATE_SCROLL_AUTHORFOLLOWINGUSERS_MAP', PoP_ServerUtils::get_template_definition('scroll-authorfollowingusers-map'));

define ('GD_TEMPLATE_SCROLL_LOCATIONS_MAP', PoP_ServerUtils::get_template_definition('scroll-locations-map'));
define ('GD_TEMPLATE_SCROLL_EVENTS_MAP', PoP_ServerUtils::get_template_definition('scroll-events-map'));
define ('GD_TEMPLATE_SCROLL_PASTEVENTS_MAP', PoP_ServerUtils::get_template_definition('scroll-pastevents-map'));

define ('GD_TEMPLATE_SCROLL_WHOWEARE_MAP', PoP_ServerUtils::get_template_definition('scroll-whoweare-map'));

class GD_EM_Template_Processor_CustomScrollMaps extends GD_Template_Processor_ScrollMapsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_ALLUSERS_MAP,
			GD_TEMPLATE_SCROLL_SEARCHUSERS_MAP,
			GD_TEMPLATE_SCROLL_AUTHORFOLLOWERS_MAP,
			GD_TEMPLATE_SCROLL_AUTHORFOLLOWINGUSERS_MAP,
			GD_TEMPLATE_SCROLL_LOCATIONS_MAP,
			GD_TEMPLATE_SCROLL_EVENTS_MAP,
			GD_TEMPLATE_SCROLL_PASTEVENTS_MAP,
			GD_TEMPLATE_SCROLL_WHOWEARE_MAP,
		);
	}


	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_SCROLL_ALLUSERS_MAP => GD_TEMPLATE_SCROLLINNER_ALLUSERS_MAP,
			GD_TEMPLATE_SCROLL_SEARCHUSERS_MAP => GD_TEMPLATE_SCROLLINNER_SEARCHUSERS_MAP,
			GD_TEMPLATE_SCROLL_AUTHORFOLLOWERS_MAP => GD_TEMPLATE_SCROLLINNER_AUTHORFOLLOWERS_MAP,
			GD_TEMPLATE_SCROLL_AUTHORFOLLOWINGUSERS_MAP => GD_TEMPLATE_SCROLLINNER_AUTHORFOLLOWINGUSERS_MAP,
			GD_TEMPLATE_SCROLL_LOCATIONS_MAP => GD_TEMPLATE_SCROLLINNER_LOCATIONS_MAP,
			GD_TEMPLATE_SCROLL_EVENTS_MAP => GD_TEMPLATE_SCROLLINNER_EVENTS_MAP,
			GD_TEMPLATE_SCROLL_PASTEVENTS_MAP => GD_TEMPLATE_SCROLLINNER_PASTEVENTS_MAP,
			GD_TEMPLATE_SCROLL_WHOWEARE_MAP => GD_TEMPLATE_SCROLLINNER_WHOWEARE_MAP,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomScrollMaps();