<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Themes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_WassupThemeMode_Base extends GD_ThemeModeBase {

	function get_theme() {

		global $gd_theme_mesym;
		return $gd_theme_mesym;
	}
}