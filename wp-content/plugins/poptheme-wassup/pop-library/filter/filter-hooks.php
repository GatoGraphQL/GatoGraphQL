<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_DataLoad_FilterHooks {

    function __construct() {
    
		add_filter(
			'gd_template:filter-authorwildcardposts:filtercomponents',
			array($this, 'wildcard_filtercomponents')
		);
		add_filter(
			'gd_template:filter-wildcardmyposts:filtercomponents',
			array($this, 'wildcard_filtercomponents')
		);
		add_filter(
			'gd_template:filter-wildcardposts:filtercomponents',
			array($this, 'wildcard_filtercomponents')
		);

		add_filter(
			'gd_template:filter-authorposts:filtercomponents',
			array($this, 'posts_filtercomponents')
		);
		add_filter(
			'gd_template:filter-myposts:filtercomponents',
			array($this, 'posts_filtercomponents')
		);
		add_filter(
			'gd_template:filter-posts:filtercomponents',
			array($this, 'posts_filtercomponents')
		);
		// add_filter(
		// 	'gd_template:filter-tagposts:filtercomponents',
		// 	array($this, 'posts_filtercomponents')
		// );
		
	}

	function wildcard_filtercomponents($filtercomponents) {
		
		// filter-tagallcontent doesn't have the hashtags, so use search there
		global $gd_filtercomponent_search, $gd_filtercomponent_hashtags, $gd_filtercomponent_appliesto, $gd_filtercomponent_categories, $gd_filtercomponent_sections;
		$pos = in_array($gd_filtercomponent_hashtags, $filtercomponents) ? array_search($gd_filtercomponent_hashtags, $filtercomponents) : array_search($gd_filtercomponent_search, $filtercomponents);
		
		// Adding needed components in reverse order because the one component we always know will be there is hashtags, it's the reference one, then we start adding from right to left
		if (PoPTheme_Wassup_Utils::add_appliesto()) {

			array_splice($filtercomponents, $pos+1, 0, array($gd_filtercomponent_appliesto));
		}
		if (PoPTheme_Wassup_Utils::add_categories()) {

			array_splice($filtercomponents, $pos+1, 0, array($gd_filtercomponent_categories));
		}
		if (PoPTheme_Wassup_Utils::add_sections()) {

			array_splice($filtercomponents, $pos+1, 0, array($gd_filtercomponent_sections));
		}

		return $filtercomponents;
	}

	function posts_filtercomponents($filtercomponents) {
		
		global $gd_filtercomponent_search, $gd_filtercomponent_hashtags, $gd_filtercomponent_appliesto, $gd_filtercomponent_categories;
		$pos = in_array($gd_filtercomponent_hashtags, $filtercomponents) ? array_search($gd_filtercomponent_hashtags, $filtercomponents) : array_search($gd_filtercomponent_search, $filtercomponents);
		
		// Adding needed components in reverse order because the one component we always know will be there is hashtags, it's the reference one, then we start adding from right to left
		if (PoPTheme_Wassup_Utils::add_appliesto()) {

			array_splice($filtercomponents, $pos+1, 0, array($gd_filtercomponent_appliesto));
		}
		if (PoPTheme_Wassup_Utils::add_categories()) {

			array_splice($filtercomponents, $pos+1, 0, array($gd_filtercomponent_categories));
		}

		return $filtercomponents;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_DataLoad_FilterHooks();