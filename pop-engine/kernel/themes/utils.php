<?php
namespace PoP\Engine\Themes;

class ThemeManagerUtils {

	public static function get_theme_dir($themename) {

		return apply_filters('\PoP\Engine\Themes\ThemeManagerUtils:get_theme_dir:'.$themename, '');
	}

	public static function get_thememode_templates_dir($themename, $thememode) {

		return self::get_theme_dir($themename).'/thememodes/'.$thememode.'/templates';
	}
}