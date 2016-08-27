<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Themes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_WassupThemeStyle_Base extends GD_ThemeStyleBase {

	function get_theme() {

		global $gd_theme_mesym;
		return $gd_theme_mesym;
	}
}