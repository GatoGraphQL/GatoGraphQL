<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-tagevents'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-tagpastevents'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTSCALENDAR', PoP_TemplateIDUtils::get_template_definition('blockgroup-tabpanel-tageventscalendar'));

class GD_EM_Template_Processor_TagSectionTabPanelBlockGroups extends GD_Template_Processor_TagSectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTSCALENDAR,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTSCALENDAR:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_TAGEVENTSCALENDAR_CALENDAR,
						GD_TEMPLATE_BLOCK_TAGEVENTSCALENDAR_CALENDARMAP,
					)
				);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLLMAP => array(),
					GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_LIST,
					),
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS:

				$ret = array(
					GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLLMAP => array(),
					GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_LIST,
					),
				);
				break;
		}

		if ($ret) {
			return apply_filters('GD_EM_Template_Processor_TagSectionTabPanelBlockGroups:panel_headers', $ret, $template_id);
		}

		return parent::get_panel_headers($template_id, $atts);
	}
	
	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS:
			
				return GD_TEMPLATE_FILTER_TAGEVENTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTSCALENDAR:
			
				return GD_TEMPLATE_FILTER_TAGEVENTSCALENDAR;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_TAGEVENTSCALENDAR_CALENDARMAP,
		);
		$calendars = array(
			GD_TEMPLATE_BLOCK_TAGEVENTSCALENDAR_CALENDAR,
		);

		if (in_array($blockunit, $details)) {

			return 'fa-th-list';
		}
		elseif (in_array($blockunit, $simpleviews)) {
			
			return 'fa-angle-right';
		}
		elseif (in_array($blockunit, $fullviews)) {
			
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

		return parent::get_panel_header_fontawesome($blockgroup, $blockunit);
	}
	function get_panel_header_title($blockgroup, $blockunit, $atts) {

		$details = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_TAGEVENTS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_TAGPASTEVENTS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_TAGEVENTSCALENDAR_CALENDARMAP,
		);
		$calendars = array(
			GD_TEMPLATE_BLOCK_TAGEVENTSCALENDAR_CALENDAR,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $simpleviews)) {
			
			return __('Feed', 'poptheme-wassup');
		}
		elseif (in_array($blockunit, $fullviews)) {
			
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

		return parent::get_panel_header_title($blockgroup, $blockunit, $atts);
	}
	function get_panel_header_tooltip($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTSCALENDAR:
				
				return $this->get_panel_header_title($blockgroup, $blockunit, $atts);
		}

		return parent::get_panel_header_tooltip($blockgroup, $blockunit);
	}

	function get_default_active_blockunit($template_id) {

		$pages_id = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS =>  POPTHEME_WASSUP_EM_PAGE_EVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS => POPTHEME_WASSUP_EM_PAGE_PASTEVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTSCALENDAR => POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR,
		);
		if ($page_id = $pages_id[$template_id]) {

			global $gd_template_settingsmanager;
			return $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG);
		}
	
		return parent::get_default_active_blockunit($template_id);
	}

	function init_atts($template_id, &$atts) {

		// Hide the tab title
		// switch ($template_id) {

		// 	case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS:
		// 	case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS:
		// 	case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTSCALENDAR:
		// 		$this->append_att($template_id, $atts, 'class', 'pop-tabtitle-hidden');
		// 		break;
		// }

		// Events: choose to only select past/future
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS:
			
				$daterange_class = 'daterange-past opens-right';
				break;
		
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS:
				
				$daterange_class = 'daterange-future opens-right';
				break;
		}
		if ($daterange_class) {
			$this->add_att(GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER, $atts, 'daterange-class', $daterange_class);
		}

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGPASTEVENTS,
		);
		$calendars = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGEVENTSCALENDAR,
		);
		if (in_array($template_id, $feeds)) {
			$class = 'feed';
		}
		elseif (in_array($template_id, $calendars)) {
			$class = 'calendar';
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
new GD_EM_Template_Processor_TagSectionTabPanelBlockGroups();
