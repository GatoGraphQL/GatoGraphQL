<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Themes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_ThemeManagerUtils {

	public static function get_theme_dir($themename) {

		return apply_filters('GD_ThemeManagerUtils:get_theme_dir:'.$themename, '');
	}

	public static function get_thememode_templates_dir($themename, $thememode) {

		return self::get_theme_dir($themename).'/thememodes/'.$thememode.'/templates';
	}
}