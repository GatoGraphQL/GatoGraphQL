<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Themes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_THEME_WASSUP', 'wassup');

class GD_Theme_Wassup extends GD_ThemeBase {

	function __construct() {

		add_filter('GD_ThemeManagerUtils:get_theme_dir:'.$this->get_name(), array($this, 'theme_dir'));
		
		// Hooks to allow the thememodes to do some functionality
		add_filter(POP_HOOK_POPFRONTEND_BACKGROUNDLOAD.':'.$this->get_name(), array($this, 'background_load'));
		add_filter(POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS.':'.$this->get_name(), array($this, 'get_framepagesections'), 10, 2);
		add_filter(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD.':'.$this->get_name(), array($this, 'get_pagesection_jsmethod'), 10, 2);
		add_filter(POP_HOOK_BLOCKSBASE_FILTERINGBYSHOWFILTER.':'.$this->get_name(), array($this, 'filteringby_showfilter'));
		add_filter(POP_HOOK_BLOCKSIDEBARS_ORIENTATION.':'.$this->get_name(), array($this, 'get_sidebar_orientation'));
		add_filter(POP_HOOK_CUSTOMTOPLEVELS_ADDPAGETABS.':'.$this->get_name(), array($this, 'add_pagetabs'));
		
		add_filter(POP_HOOK_POPMANAGERUTILS_EMBEDURL.':'.$this->get_name(), array($this, 'get_embed_url'));
		add_filter(POP_HOOK_POPMANAGERUTILS_PRINTURL.':'.$this->get_name(), array($this, 'get_print_url'));
		add_filter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN.':'.$this->get_name(), array($this, 'is_main_scrollable'));

		// ThemeStyle
		add_filter(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE.':'.$this->get_name(), array($this, 'get_pagesectionside_logosize'));
		add_filter(POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS.':'.$this->get_name(), array($this, 'get_blockgroup_topwidgets_incolumns'));
		add_filter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS.':'.$this->get_name(), array($this, 'get_carousel_users_gridclass'));
		add_filter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID.':'.$this->get_name(), array($this, 'get_scrollinner_thumbnail_grid'));
		
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED.':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype_feed'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE.':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype_table'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CAROUSEL.':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype_carousel'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR.':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype_calendar'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_MAP.':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype_map'));
		add_filter(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDARMAP.':'.$this->get_name(), array($this, 'get_settingsprocessors_blocktype_calendarmap'));
		
		// add_filter(POP_HOOK_SW_APPSHELL_REOPENTABS.':'.$this->get_name(), array($this, 'reopenTabs'));
		// add_filter(POP_HOOK_PAGETABS_ADDOPENTAB.':'.$this->get_name(), array($this, 'addOpenTab'));
		add_filter(POP_HOOK_POPFRONTEND_KEEPOPENTABS.':'.$this->get_name(), array($this, 'keepOpenTabs'));

		parent::__construct();
	}

	function get_name() {
		
		return GD_THEME_WASSUP;
	}

	function theme_dir($dir) {

		return dirname(__FILE__);
	}

	function get_default_thememodename() {

		// Allow to override this value. Eg: GetPoP needs the Simple theme.
		return apply_filters(
			'GD_Theme_Wassup:thememode:default',
			GD_THEMEMODE_WASSUP_SLIDING
		);
	}

	function get_default_themestylename() {

		// Allow to override this value. Eg: GetPoP needs the Simple theme.
		return apply_filters(
			'GD_Theme_Wassup:themestyle:default',
			GD_THEMESTYLE_WASSUP_SWIFT
		);
	}

	// function addOpenTab($bool) {

	// 	$filtername = sprintf(
	// 		'%s:%s:%s',
	// 		POP_HOOK_PAGETABS_ADDOPENTAB,
	// 		$this->get_name(),
	// 		$this->get_themestyle()->get_name()
	// 	);
	// 	return apply_filters($filtername, $bool);
	// }
	// function reopenTabs($bool) {

	// 	$filtername = sprintf(
	// 		'%s:%s:%s',
	// 		POP_HOOK_SW_APPSHELL_REOPENTABS,
	// 		$this->get_name(),
	// 		$this->get_themestyle()->get_name()
	// 	);
	// 	return apply_filters($filtername, $bool);
	// }
	function keepOpenTabs($bool) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_POPFRONTEND_KEEPOPENTABS,
			$this->get_name(),
			$this->get_thememode()->get_name()
		);
		return apply_filters($filtername, $bool);
	}
	function get_settingsprocessors_blocktype_feed($type) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_table($type) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_carousel($type) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CAROUSEL,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_calendar($type) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_map($type) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_MAP,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $type);
	}
	function get_settingsprocessors_blocktype_calendarmap($type) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDARMAP,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $type);
	}

	function get_scrollinner_thumbnail_grid($grid) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $grid);
	}

	function get_carousel_users_gridclass($class) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_CAROUSEL_USERS_GRIDCLASS,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $class);
	}

	function get_blockgroup_topwidgets_incolumns($incolumns) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $incolumns);
	}

	function get_pagesectionside_logosize($size) {

		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE,
			$this->get_name(),
			$this->get_themestyle()->get_name()
		);
		return apply_filters($filtername, $size);
	}

	function background_load($pages) {

		// Forward the filter to be processed by the ThemeMode
		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_POPFRONTEND_BACKGROUNDLOAD,
			$this->get_name(),
			$this->get_thememode()->get_name()
		);
		return apply_filters($filtername, $pages);
	}
	function get_framepagesections($pagesections, $template_id) {

		// Forward the filter to be processed by the ThemeMode
		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS,
			$this->get_name(),
			$this->get_thememode()->get_name()
		);
		return apply_filters($filtername, $pagesections, $template_id);
	}
	function get_pagesection_jsmethod($jsmethod, $template_id) {

		// Forward the filter to be processed by the ThemeMode
		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD,
			$this->get_name(),
			$this->get_thememode()->get_name()
		);
		return apply_filters($filtername, $jsmethod, $template_id);
	}
	function filteringby_showfilter($showfilter) {

		// Forward the filter to be processed by the ThemeMode
		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_BLOCKSBASE_FILTERINGBYSHOWFILTER,
			$this->get_name(),
			$this->get_thememode()->get_name()
		);
		return apply_filters($filtername, $showfilter);
	}
	function get_sidebar_orientation($orientation) {

		// Forward the filter to be processed by the ThemeMode
		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_BLOCKSIDEBARS_ORIENTATION,
			$this->get_name(),
			$this->get_thememode()->get_name()
		);
		return apply_filters($filtername, $orientation);
	}
	function add_pagetabs($add) {

		// Forward the filter to be processed by the ThemeMode
		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_CUSTOMTOPLEVELS_ADDPAGETABS,
			$this->get_name(),
			$this->get_thememode()->get_name()
		);
		return apply_filters($filtername, $add);
	}

	function get_embed_url($url) {

		return $this->add_url_params(add_query_arg(GD_URLPARAM_THEMEMODE, GD_THEMEMODE_WASSUP_EMBED, $url));
	}
	function get_print_url($url) {

		// Also add param to print automatically
		return $this->add_url_params(add_query_arg(GD_URLPARAM_ACTION, GD_URLPARAM_ACTION_PRINT, add_query_arg(GD_URLPARAM_THEMEMODE, GD_THEMEMODE_WASSUP_PRINT, $url)));
	}

	function is_main_scrollable($value) {

		// Forward the filter to be processed by the ThemeMode
		$filtername = sprintf(
			'%s:%s:%s',
			POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN,
			$this->get_name(),
			$this->get_thememode()->get_name()
		);
		return apply_filters($filtername, $value);
	}


	
	protected function add_url_params($url) {

		$vars = GD_TemplateManager_Utils::get_vars();

		// Add the themestyle, if it is not the default one
		if (!$vars['themestyle-isdefault']) {
			$url = add_query_arg(GD_URLPARAM_THEMESTYLE, $vars['themestyle'], $url);
		}

		return $url;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_theme_mesym;
$gd_theme_mesym = new GD_Theme_Wassup();
