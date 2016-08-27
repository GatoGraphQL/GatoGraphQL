<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * qTranslate plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Register the language scripts for the fullcalendar
add_action("wp_enqueue_scripts", 'em_popprocessors_qtransx_register_scripts');
function em_popprocessors_qtransx_register_scripts() {

	// List of supported languages in fullcalendar. Note that we have both 'zh-cn' and 'zh-tw', so this can be overriden
	// 'en' is hardcoded in the original file, so no need to handle (unless it must be overriden with other country, such as 'en-au')
	$languages = apply_filters(
		'em_popprocessors_qtransx_register_scripts:languages',
		array(
			'es' => 'es',
			'zh' => 'zh-cn',
		)
	);

	// If the current lang is supported, then use fullcalendar's localization file
	if ($lang = $languages[qtranxf_getLanguage()]) {

		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			$placeholder = 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.9.1/lang/%s.js';
		}
		else {

			$placeholder = EM_POPPROCESSORS_URI.'/js/includes/cdn/fullcalendar.2.9.1-lang/%s.js';
		}
		
		$js_file = sprintf(
			$placeholder,
			$lang
		);
		wp_register_script('fullcalendar-lang', $js_file, array('fullcalendar'), null);
		wp_enqueue_script('fullcalendar-lang');
	}
}