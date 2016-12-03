<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_GF_DataLoad_FilterHooks {

    function __construct() {

		add_filter(
			'gd_template:filter:filtercomponents:maybevolunteersneeded',
			array($this, 'posts_filtercomponents')
		);
		
	}

	function posts_filtercomponents($filtercomponents) {
		
		global $gd_filtercomponent_search, $gd_filtercomponent_hashtags, $gd_filtercomponent_appliesto, $gd_filtercomponent_volunteersneeded;
		$pos = in_array($gd_filtercomponent_hashtags, $filtercomponents) ? array_search($gd_filtercomponent_hashtags, $filtercomponents) : array_search($gd_filtercomponent_search, $filtercomponents);
		
		if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {

			array_splice($filtercomponents, $pos+1, 0, array($gd_filtercomponent_volunteersneeded));
		}

		return $filtercomponents;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_GF_DataLoad_FilterHooks();