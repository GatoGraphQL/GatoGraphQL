<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOSTLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-webpostlink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOST_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-webpost-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-highlight-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATEBTN', PoP_ServerUtils::get_template_definition('custom-buttoninner-highlight-createbtn'));

class Wassup_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOSTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOST_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATEBTN,
		);
	}

	function get_fontawesome($template_id, $atts) {

		$icons = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOSTLINK_CREATE => POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOST_CREATE => POPTHEME_WASSUP_PAGE_ADDWEBPOST,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATE => POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATEBTN => POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT,
		);
		if ($icon = $icons[$template_id]) {

			return 'fa-fw '.gd_navigation_menu_item($icon, false);
		}
		
		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		// $extract = __('Extract important information', 'poptheme-wassup');
		$extract = __('Add Highlight', 'poptheme-wassup');
		$titles = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOSTLINK_CREATE => __('Link', 'poptheme-wassup'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOST_CREATE => __('Post', 'poptheme-wassup'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATE => $extract,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATEBTN => $extract,
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_ButtonInners();