<?php

class PoPTheme_Wassup_Manager {

	function __construct() {

		// Catch hooks and forward them to the Themes and further on ThemeMods for their processing
		add_filter(POP_HOOK_POPFRONTEND_BACKGROUNDLOAD, array($this, 'background_load'));
		add_filter(POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS, array($this, 'get_framepagesections'), 10, 2);
		add_filter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD, array($this, 'get_pagesectionjsmethod'), 10, 2);
		add_filter(POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD, array($this, 'get_blockjsmethod'), 10, 2);
		add_filter(POP_HOOK_BLOCKSBASE_FILTERINGBYSHOWFILTER, array($this, 'filteringby_showfilter'));
		add_filter(POP_HOOK_CUSTOMTOPLEVELS_ADDPAGETABS, array($this, 'add_pagetabs'));
		add_filter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, array($this, 'get_blocksidebars_orientation'));
		
		add_filter(POP_HOOK_POPMANAGERUTILS_EMBEDURL, array($this, 'get_embed_url'));
		add_filter(POP_HOOK_POPMANAGERUTILS_PRINTURL, array($this, 'get_print_url'));
		add_filter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN, array($this, 'is_main_scrollable'));

		// ThemeStyle
		add_filter(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE, array($this, 'get_pagesectionside_logosize'));
		add_filter(POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS, array($this, 'get_blockgroup_topwidgets_incolumns'));
		add_filter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS, array($this, 'get_carousel_users_gridclass'));
		add_filter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID, array($this, 'get_scrollinner_thumbnail_grid'));
		
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED, array($this, 'get_settingsprocessors_blocktype_feed'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE, array($this, 'get_settingsprocessors_blocktype_table'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CAROUSEL, array($this, 'get_settingsprocessors_blocktype_carousel'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR, array($this, 'get_settingsprocessors_blocktype_calendar'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_MAP, array($this, 'get_settingsprocessors_blocktype_map'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDARMAP, array($this, 'get_settingsprocessors_blocktype_calendarmap'));
		
		// openTabs
		// add_filter(POP_HOOK_SW_APPSHELL_REOPENTABS, array($this, 'reopenTabs'));
		// add_filter(POP_HOOK_PAGETABS_ADDOPENTAB, array($this, 'addOpenTab'));
		add_filter(POP_HOOK_POPFRONTEND_KEEPOPENTABS, array($this, 'keepOpenTabs'));
	}

	// function addOpenTab($bool) {

	// 	$filtername = sprintf(
	// 		'%s:%s',
	// 		POP_HOOK_PAGETABS_ADDOPENTAB,
	// 		GD_TemplateManager_Utils::get_theme()->get_name()
	// 	);
	// 	return apply_filters($filtername, $bool);
	// }
	// function reopenTabs($bool) {

	// 	$filtername = sprintf(
	// 		'%s:%s',
	// 		POP_HOOK_SW_APPSHELL_REOPENTABS,
	// 		GD_TemplateManager_Utils::get_theme()->get_name()
	// 	);
	// 	return apply_filters($filtername, $bool);
	// }
	function keepOpenTabs($bool) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_POPFRONTEND_KEEPOPENTABS,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $bool);
	}

	function get_settingsprocessors_blocktype_feed($type) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_table($type) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_carousel($type) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CAROUSEL,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_calendar($type) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_map($type) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_MAP,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_calendarmap($type) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDARMAP,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_scrollinner_thumbnail_grid($grid) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $grid);
	}
	function get_carousel_users_gridclass($class) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_CAROUSEL_USERS_GRIDCLASS,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $class);
	}
	function get_blockgroup_topwidgets_incolumns($incolumns) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $incolumns);
	}
	function get_pagesectionside_logosize($size) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $size);
	}
	function background_load($pages) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_POPFRONTEND_BACKGROUNDLOAD,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $pages);
	}
	function get_framepagesections($pagesections, $template_id) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $pagesections, $template_id);
	}
	function get_pagesectionjsmethod($jsmethod, $template_id) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $jsmethod, $template_id);
	}
	function get_blockjsmethod($jsmethod, $template_id) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $jsmethod, $template_id);
	}
	function filteringby_showfilter($showfilter) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_BLOCKSBASE_FILTERINGBYSHOWFILTER,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $showfilter);
	}
	function add_pagetabs($add) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_CUSTOMTOPLEVELS_ADDPAGETABS,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $add);
	}	
	function get_blocksidebars_orientation($orientation) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_BLOCKSIDEBARS_ORIENTATION,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $orientation);
	}

	function get_embed_url($url) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_POPMANAGERUTILS_EMBEDURL,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $url);
	}
	function get_print_url($url) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_POPMANAGERUTILS_PRINTURL,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $url);
	}
	function is_main_scrollable($value) {

		$filtername = sprintf(
			'%s:%s',
			POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN,
			GD_TemplateManager_Utils::get_theme()->get_name()
		);
		return apply_filters($filtername, $value);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Manager();
