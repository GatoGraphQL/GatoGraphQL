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

	global $q_config;
	$languages = $q_config['enabled_languages'];
	
	// Add the language links
	if ($languages && count($languages) > 1) {
		
		$current = qtranxf_getLanguage();
		$url = trailingslashit(home_url());
		$shortnames = array(
			'en' => 'EN',
			'ms' => 'BM',
			'es' => 'ES',
		);

		$items = array();
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
		if ($items) {
			$title .= sprintf(
				// Comment Leo 02/07/2016: Changed for ThemeStyle Swift
				// '&nbsp;&nbsp;&nbsp;<small>%s</small>',
				'<br/><small>%s</small>',
				implode('&nbsp;', $items)
			);
		}
	}

	return $title;
}
