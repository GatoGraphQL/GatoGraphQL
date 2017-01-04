<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * qTranslate plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_translate', 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage');

/**---------------------------------------------------------------------------------------------------------------
 * Replace the "no alternate language available" message
 * Otherwise it prints:
 * Sorry, this entry is only available in Malay and English. For the sake of viewer convenience, the content is shown below in this site default language. You may click one of the links to switch the site language to another available language.
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('i18n_content_translation_not_available', 'gd_qtransxf_use_block_altlang', 10, 7);
function gd_qtransxf_use_block_altlang($output, $lang, $language_list, $alt_lang, $alt_content, $msg, $q_config) {

	return sprintf(
		'<p class="text-warning bg-warning">%s</p><hr/>%s',
		sprintf(
			__('Ops, it seems this content is not available in <strong>%s</strong>. <a href="%s">Would you like to help us translate it?</a>', 'poptheme-wassup'),
			$q_config['language_name'][$lang],
			get_permalink(POPTHEME_WASSUP_GF_PAGE_CONTACTUS)
		),
		$alt_content
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * Litte bit of everything
 * ---------------------------------------------------------------------------------------------------------------*/

/* 
 * Add extra classes to the body: Locale
 */
add_filter("gd_classes_body", "gd_body_class_locale");	 
function gd_body_class_locale($body_classes) {

	// Language
	$body_classes[] = "body-".qtranxf_getLanguage();
	
	return $body_classes;
}


// Add locale to fileupload-userphoto
add_filter('gd_fileupload-userphoto_locale', 'gd_fileupload_userphoto_locale_impl');
function gd_fileupload_userphoto_locale_impl($locale_path) {

	$locale = '-' . qtranxf_getLanguage();
	$locale_path = str_replace('/locale.js', '/locale'.$locale.'.js', $locale_path);
	return $locale_path;
}

/**---------------------------------------------------------------------------------------------------------------
 * Add language to Ajax url
 * ---------------------------------------------------------------------------------------------------------------*/

// Add the locale to the frontend
add_filter('gd_templatemanager:locale', 'pop_qtrans_locale');
function pop_qtrans_locale($locale) {

	// Send the language
	return qtranxf_getLanguage();
}

// Add the locale to the home url
add_filter('gd_templatemanager:homelocale_url', 'pop_qtrans_homelocale_url');
function pop_qtrans_homelocale_url($url) {

	// home_url() already contains the language information
	return trailingslashit(home_url());
}

/**---------------------------------------------------------------------------------------------------------------
 * Add language to Ajax url
 * ---------------------------------------------------------------------------------------------------------------*/

// add_filter("gd_ajax_url", "gd_qtrans_ajax_url");	 
// function gd_qtrans_ajax_url($ajaxurl) {

// 	$ajaxurl = add_query_arg('lang', qtranxf_getLanguage(), $ajaxurl);
	
// 	return $ajaxurl;
// }
