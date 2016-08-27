<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Themes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter("gd_classes_body", 'gd_wassup_theme_body_class');
function gd_wassup_theme_body_class($body_classes) {

	$vars = GD_TemplateManager_Utils::get_vars();
	if ($vars['theme'] == GD_THEME_WASSUP) {

		$thememode = $vars['thememode'];

		// For the 'simple' and 'embed' themes, also add 'sliding' in the body class, since these are sliding implementations and need its css classes
		$addclass_thememodes = array(
			GD_THEMEMODE_WASSUP_SIMPLE,
			GD_THEMEMODE_WASSUP_EMBED,
		);
		if (in_array($thememode, $addclass_thememodes)) {

			$body_classes[] = GD_THEMEMODE_WASSUP_SLIDING;
		}

		// Add the offcanvas class when appropriate
		$offcanvas = array(
			GD_THEMEMODE_WASSUP_SIMPLE,
			GD_THEMEMODE_WASSUP_EMBED,
			GD_THEMEMODE_WASSUP_SLIDING,
		);
		if (in_array($thememode, $offcanvas)) {

			$body_classes[] = 'nooverflow';
		}
	}
	
	return $body_classes;
}