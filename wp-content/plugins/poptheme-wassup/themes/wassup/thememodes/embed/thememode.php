<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * ThemeMode Embed
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_THEMEMODE_WASSUP_EMBED', 'embed');

class GD_ThemeMode_Wassup_Embed extends GD_ThemeMode_Wassup_Simple {

	function __construct() {

		add_filter('gd_jquery_constants', array($this, 'jquery_constants'));

		// Hooks to allow the thememodes to do some functionality
		add_filter(POP_HOOK_BLOCKSBASE_FILTERINGBYSHOWFILTER.':'.$this->get_theme()->get_name().':'.$this->get_name(), array($this, 'filteringby_showfilter'));
		add_filter(POP_HOOK_POPFRONTEND_KEEPOPENTABS.':'.$this->get_theme()->get_name().':'.$this->get_name(), '__return_false');
		
		// The embed must make the main pageSection scrollable using perfect-scrollbar, so that the fullscreen mode works fine
		add_filter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN.':'.$this->get_theme()->get_name().':'.$this->get_name(), '__return_true');

		parent::__construct();
	}

	function jquery_constants($jquery_constants) {

		$jquery_constants['THEMEMODE_WASSUP_EMBED'] = GD_THEMEMODE_WASSUP_EMBED;
		return $jquery_constants;
	}

	function get_name() {
		
		return GD_THEMEMODE_WASSUP_EMBED;
	}

	function get_framepagesections($pagesections, $template_id) {

		// Same as ThemeMode Simple, however we don't need the Navigator
		$pagesections = parent::get_framepagesections($pagesections, $template_id);

		array_splice($pagesections, array_search(GD_TEMPLATE_PAGESECTION_SIDE, $pagesections), 1);
		array_splice($pagesections, array_search(GD_TEMPLATE_PAGESECTION_NAVIGATOR, $pagesections), 1);

		// Replace the TopSimple with TopEmbed
		array_splice($pagesections, array_search(GD_TEMPLATE_PAGESECTION_TOPSIMPLE, $pagesections), 1, array(GD_TEMPLATE_PAGESECTION_TOPEMBED));

		return $pagesections;
	}

	function filteringby_showfilter($showfilter) {

		return false;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_ThemeMode_Wassup_Embed();
