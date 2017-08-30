<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * User Role Editor plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('GD_Template_CacheProcessor:add_vars', 'gd_qtransx_cacheprocessor_addvars');
function gd_qtransx_cacheprocessor_addvars($filename) {

	// Add the language
	// $filename .= '-lang_'.qtranxf_getLanguage();
	$filename .= '-'.qtranxf_getLanguage();

	return $filename;
}

// add_filter('GD_Template_Processor_CustomBlockGroups:welcometitle', 'gd_qtransx_welcome_languagelinks', 10, 2);
add_filter('GD_Template_Processor_CustomBlockGroups:homewelcometitle', 'gd_qtransx_welcome_languagelinks');
// function gd_qtransx_welcome_languagelinks($title, $template_id) {
function gd_qtransx_welcome_languagelinks($title) {

	// Add the language links
	$shortnames = array(
		'en' => 'EN',
		'ms' => 'BM',
		'es' => 'ES',
		'zh' => 'ZH',
	);
	if ($items = get_qtransx_languageitems($shortnames)) {
		$title .= sprintf(
			// // Comment Leo 02/07/2016: Changed for ThemeStyle Swift
			// // '&nbsp;&nbsp;&nbsp;<small>%s</small>',
			// '<br/><small>%s</small>',
			'<span class="language-links">%s</span>',
			implode('&nbsp;', $items)
		);
	}

	return $title;
}

function get_qtransx_languageitems($shortnames = array()) {

	$items = array();

	global $q_config;
	$languages = $q_config['enabled_languages'];
	
	// Add the language links
	if ($languages && count($languages) > 1) {
		
		$current = qtranxf_getLanguage();
		$url = trailingslashit(home_url());

		foreach($languages as $lang) {
			$name = qtranxf_getLanguageName($lang);
			$shortname = $shortnames[$lang] ? $shortnames[$lang] : $name;
			if ($current == $lang) {
				$items[] = $shortname;
			}
			else {
				$items[] = sprintf(
					'<a href="%s" target="%s" title="%s">%s</a>',
					qtranxf_convertURL($url, $lang),
					GD_URLPARAM_TARGET_FULL,
					$name,
					$shortname
				);
			}
		}
	}

	return $items;
}

/**---------------------------------------------------------------------------------------------------------------
 * Add the language to the links to PoP and Verticals
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_CustomPageSections:footer:poweredby-links', 'gd_qtransx_footerlinks');
function gd_qtransx_footerlinks($link) {

	// Because both PoP and Verticals are in EN and ES languages, add the corresponding language if the current website supports them
	$current = qtranxf_getLanguage();
	if (in_array($current, POPTHEME_WASSUP_QTRANS_LANG_POWEREDBYWEBSITES)) {

		// $link = qtranxf_convertURL($link, $current);
		$link = trailingslashit($link).$current.'/';
	}

	return $link;
}