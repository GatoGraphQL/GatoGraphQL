<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS', PoP_TemplateIDUtils::get_template_definition('blockgroup-events-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS', PoP_TemplateIDUtils::get_template_definition('blockgroup-pastevents-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTSCALENDAR', PoP_TemplateIDUtils::get_template_definition('blockgroup-eventscalendar-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS', PoP_TemplateIDUtils::get_template_definition('blockgroup-myevents-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS', PoP_TemplateIDUtils::get_template_definition('blockgroup-mypastevents-tabpanel'));

class GD_EM_Template_Processor_SectionTabPanelBlockGroups extends GD_Template_Processor_SectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTSCALENDAR,
			
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS,
		);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS:

				// Only reload/destroy if these are main blocks
				if ($this->get_att($template_id, $atts, 'is-mainblock')) {
					$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
					$this->add_jsmethod($ret, 'refetchBlockGroupOnUserLoggedIn');
				}
				break;
		}

		return $ret;
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_EVENTS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTSCALENDAR:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR,
						GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDARMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYEVENTS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYPASTEVENTS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS:

				return array(
					GD_TEMPLATE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_EVENTS_SCROLLMAP => array(),
					GD_TEMPLATE_BLOCK_EVENTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_EVENTS_SCROLL_LIST,
					),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS:

				return array(
					GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_LIST,
					),
					GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLLMAP => array(),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS:

				return array(
					GD_TEMPLATE_BLOCK_MYEVENTS_TABLE_EDIT => array(),
					GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW => array(
						GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW,
					),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS:

				return array(
					GD_TEMPLATE_BLOCK_MYPASTEVENTS_TABLE_EDIT => array(),
					GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW => array(
						GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW,
					),
				);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	protected function get_controlgroup_top($template_id) {

		// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS:

					return GD_TEMPLATE_CONTROLGROUP_EVENTLIST;

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTSCALENDAR:

					return GD_TEMPLATE_CONTROLGROUP_POSTLIST;
				
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS:

					return GD_TEMPLATE_CONTROLGROUP_MYEVENTLIST;

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS:

					return GD_TEMPLATE_CONTROLGROUP_MYPOSTLIST;
			}
		}

		return parent::get_controlgroup_top($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS:
			
				return GD_TEMPLATE_FILTER_EVENTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS:
			
				return GD_TEMPLATE_FILTER_MYEVENTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTSCALENDAR:
			
				return GD_TEMPLATE_FILTER_EVENTSCALENDAR;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDARMAP,
		);
		$calendars = array(
			GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYEVENTS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYPASTEVENTS_TABLE_EDIT,
		);
		$simpleviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW,
		);

		if (in_array($blockunit, $details)) {

			return 'fa-th-list';
		}
		elseif (in_array($blockunit, $simpleviews) || in_array($blockunit, $simpleviewpreviews)) {
			
			return 'fa-angle-right';
		}
		elseif (in_array($blockunit, $fullviews) || in_array($blockunit, $fullviewpreviews)) {
			
			return 'fa-angle-double-right';
		}
		elseif (in_array($blockunit, $thumbnails)) {
			
			return 'fa-th';
		}
		elseif (in_array($blockunit, $lists)) {
			
			return 'fa-list';
		}
		elseif (in_array($blockunit, $maps)) {
			
			return 'fa-map-marker';
		}
		elseif (in_array($blockunit, $calendars)) {
			
			return 'fa-calendar-o';
		}
		elseif (in_array($blockunit, $edits)) {
			
			return 'fa-edit';
		}
		// elseif (in_array($blockunit, $fullviewpreviews)) {
			
		// 	return 'fa-eye';
		// }

		return parent::get_panel_header_fontawesome($blockgroup, $blockunit);
	}
	function get_panel_header_title($blockgroup, $blockunit, $atts) {

		$details = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDARMAP,
		);
		$calendars = array(
			GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYEVENTS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYPASTEVENTS_TABLE_EDIT,
		);
		$simpleviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $simpleviews) || in_array($blockunit, $simpleviewpreviews)) {
			
			return __('Feed', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $fullviews) || in_array($blockunit, $fullviewpreviews)) {
			
			return __('Extended', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $thumbnails)) {
			
			return __('Thumbnail', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $lists)) {
			
			return __('List', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $maps)) {
			
			return __('Map', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $calendars)) {
			
			return __('Calendar', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $edits)) {
			
			return __('Edit', 'poptheme-wassup');
		}
		// elseif (in_array($blockunit, $simpleviewpreviews)) {
			
		// 	return __('Simple View/Preview', 'poptheme-wassup');
		// }
		// elseif (in_array($blockunit, $fullviewpreviews)) {
			
		// 	return __('Full View/Preview', 'poptheme-wassup');
		// }

		return parent::get_panel_header_title($blockgroup, $blockunit, $atts);
	}

	function init_atts($template_id, &$atts) {

		// Events: choose to only select past/future
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS:

				$daterange_class = 'daterange-past opens-right';
				break;
		
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS:
				
				$daterange_class = 'daterange-future opens-right';
				break;
		}
		if ($daterange_class) {
			$this->add_att(GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER, $atts, 'daterange-class', $daterange_class);
		}

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS,
		);
		$calendars = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTSCALENDAR,
		);
		$tables = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS,
		);
		if (in_array($template_id, $feeds)) {
			$class = 'feed';
		}
		elseif (in_array($template_id, $calendars)) {
			$class = 'calendar';
		}
		elseif (in_array($template_id, $tables)) {
			$class = 'tableblock';
		}
		if ($class) {
			$this->append_att($template_id, $atts, 'class', $class);
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_SectionTabPanelBlockGroups();
