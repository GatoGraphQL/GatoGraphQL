<?php

/**---------------------------------------------------------------------------------------------------------------
 * themes.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_ThemeManager:default', 'gd_custom_default_theme');
function gd_custom_default_theme($default) {

	return GD_THEME_WASSUP;
}