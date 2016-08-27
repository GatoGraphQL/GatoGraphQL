<?php

/**
 * List supported languages of the viewer (not the plugin)
 *
 * @since   2.5.0.1
 * @note	Last updated from Google supported list: 12 Nov 2012
 * @return  Array of Google Docs supported languages
 */
function gde_supported_langs() {
	$langs = array(
		// format: code => description
		'in'	=> 'Bahasa Indonesia',
		'ca'	=> 'Català',
		'cs'	=> 'Čeština',
		'da'	=> 'Dansk',
		'de'	=> 'Deutsch',
		'en_GB'	=> 'English (UK)',
		'en_US'	=> 'English (US)',
		'es'	=> 'Español',
		'fil'	=> 'Filipino',
		'fr'	=> 'Français',
		'hr'	=> 'Hrvatski',
		'it'	=> 'Italiano',
		'lv'	=> 'Latviešu',
		'lt'	=> 'Lietuvių',
		'hu'	=> 'Magyar',
		'nl'	=> 'Nederlands',
		'no'	=> 'Norsk',
		'pl'	=> 'Polski',
		'pt_BR'	=> 'Português (Brasil)',
		'pt_PT'	=> 'Português (Portugal)',
		'ro'	=> 'Română',
		'sk'	=> 'Slovenčina',
		'sl'	=> 'Slovenščina',
		'fi'	=> 'Suomi',
		'sv'	=> 'Svenska',
		'vi'	=> 'Tiếng Việt',
		'tr'	=> 'Türkçe',
		'el'	=> 'Ελληνικά',
		'bg'	=> 'Български',
		'ru'	=> 'Русский',
		'sr' 	=> 'Српски',
		'uk'	=> 'Українська',
		'iw'	=> 'עברית',
		'ar'	=> 'العربية',
		'mr'	=> 'मराठी',
		'hi'	=> 'हिन्दी',
		'bn'	=> 'বাংলা',
		'gu'	=> 'ગુજરાતી',
		'ta'	=> 'தமிழ்',
		'te'	=> 'తెలుగు',
		'kn'	=> 'ಕನ್ನಡ',
		'ml'	=> 'മലയാളം',
		'th'	=> 'ไทย',
		'zh_CN'	=> '中文（中国）',
		'zh_TW'	=> '中文（台灣）',
		'ja'	=> '日本語',
		'ko'	=> '한국어'
	);
	return $langs;
}

/**
 * List of WordPress supported langs mapped to Google lang codes
 *
 * @since   2.5.0.4
 * @note	Last updated from wordpress.org: 12 Nov 2012
 * @return  Array of Google Docs supported languages
 */
function gde_mapped_langs( $locale = '' ) {
	$langs = array(
		// wp_lang locale	=>	Google lang code
		'ar'	=>	'ar',		// Arabic
		'bg_BG'	=>	'bg',		// Bulgarian
		'bn_BD'	=>	'bn',		// Bengala
		'ca'	=>	'ca',		// Catalan
		'cs_CZ'	=>	'cs',		// Czech
		'da_DK'	=>	'da',		// Danish
		'de_DE'	=>	'de',		// German
		'el'	=>	'el',		// Greek
		'en_GB'	=>	'en_GB',	// English UK
		'en_US'	=>	'en_US',	// English USA
		'es_ES'	=>	'es',		// Spanish
		'fi'	=>	'fi',		// Finnish
		'fr_FR'	=>	'fr',		// French
		'he_IL'	=>	'iw',		// Hebrew
		'hi_IN'	=>	'hi',		// India (Hindi)
		'hu_HU'	=>	'hu',		// Magyar (Hungarian)
		'hr'	=>	'hr',		// Croatian
		'id_ID'	=>	'in',		// Bahasa Indonesian
		'it_IT'	=>	'it',		// Italian
		'ja'	=>	'ja',		// Japanese
		'ko_KR'	=>	'ko',		// Korean
		'lt_LT'	=>	'lt',		// Lithuanian
		'lv'	=>	'lv',		// Latvian
		'nl_NL'	=>	'nl',		// Netherlands (Dutch)
		'nb_NO'	=>	'no',		// Norwegian (Norsk)
		'nn_NO'	=>	'no',		
		'pl_PL'	=>	'pl',		// Polish
		'pt_BR'	=>	'pt_BR',	// Portuguese (Brazil)
		'pt_PT'	=>	'pt_PT',	// Portuguese (Portugal)
		'ro_RO'	=>	'ro',		// Romanian
		'ru_RU'	=>	'ru',		// Russian
		'sk_SK'	=>	'sk',		// Slovak
		'sl_SL'	=>	'sl',		// Slovenian
		'sr_RS'	=>	'sr',		// Serbian
		'sv_SE'	=>	'sv',		// Swedish
		'ta_LK'	=>	'ta',		// Tamil (Sri Lanka)
		'th'	=>	'th',		// Thai
		'tr'	=>	'tr',		// Turkish
		'uk'	=>	'uk',		// Ukranian
		'vi'	=>	'vi',		// Vietnamese
		'zh_CN'	=>	'zh_CN',	// Chinese
		'zh_TW'	=>	'zh_TW'		// Chinese (Taiwan)
	);
	
	if ( ! empty( $locale ) && array_key_exists( $locale, $langs ) ) {
		return $langs[$locale];
	} else {
		return "en_US";
	}
}

?>
