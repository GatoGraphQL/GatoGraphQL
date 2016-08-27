<?php

/**---------------------------------------------------------------------------------------------------------------
 * Styles
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PopThemeWassup_AAL_Template_Processor_BackgroundColorStyleLayouts:bgcolor', 'poptheme_wassup_bgcolor', 10, 2);
function poptheme_wassup_bgcolor($color, $template_id) {

	switch ($template_id) {

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:

			return '#fff';

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:

			// Same as alert-info background color
			return '#d9edf7';

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:

			return '#4d4d4f';

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:

			// Same as btn-primary background color
			return '#337ab7';
	}

	return $color;
}