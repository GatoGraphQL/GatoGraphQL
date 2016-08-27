<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AnchorMenusBase extends GD_Template_Processor_ContentsBase {

	function get_inner_template($template_id) {

		return GD_TEMPLATE_CONTENTINNER_MENU_BUTTON;
	}
}
