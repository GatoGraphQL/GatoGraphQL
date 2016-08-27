<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_BUTTON_WEBPOSTLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-webpostlink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_WEBPOST_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-webpost-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-highlight-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN', PoP_ServerUtils::get_template_definition('custom-postbutton-highlight-createbtn'));

class Wassup_Template_Processor_PostButtons extends GD_Custom_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_BUTTON_WEBPOSTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_WEBPOST_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN,
		);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_CUSTOM_TEMPLATE_BUTTON_WEBPOSTLINK_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOSTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_WEBPOST_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_WEBPOST_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN => GD_CUSTOM_TEMPLATE_BUTTONINNER_HIGHLIGHT_CREATEBTN,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_selectabletypeahead_template($template_id) {

		switch ($template_id) {
					
			case GD_CUSTOM_TEMPLATE_BUTTON_WEBPOSTLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_WEBPOST_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES;
		}

		return parent::get_selectabletypeahead_template($template_id);
	}

	function get_linktarget($template_id, $atts) {

		switch ($template_id) {
					
			case GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN:

				return GD_URLPARAM_TARGET_ADDONS;
					
			case GD_CUSTOM_TEMPLATE_BUTTON_WEBPOST_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_WEBPOSTLINK_CREATE:

				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {
					
			case GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN:

				$ret .= 'btn btn-link';
				break;
		}

		return $ret;
	}

	function get_title($template_id) {

		// $extract = __('Extract important information', 'poptheme-wassup');
		$extract = __('Add Highlight', 'poptheme-wassup');
		$titles = array(
			GD_CUSTOM_TEMPLATE_BUTTON_WEBPOSTLINK_CREATE => __('Link', 'poptheme-wassup'),
			GD_CUSTOM_TEMPLATE_BUTTON_WEBPOST_CREATE => __('Post', 'poptheme-wassup'),
			GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATE => $extract,
			GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN => $extract,
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_title($template_id);
	}

	function get_url_field($template_id) {

		$fields = array(
			GD_CUSTOM_TEMPLATE_BUTTON_WEBPOSTLINK_CREATE => 'addwebpostlink-url',
			GD_CUSTOM_TEMPLATE_BUTTON_WEBPOST_CREATE => 'addwebpost-url',
			GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATE => 'addhighlight-url',
			GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN => 'addhighlight-url',
		);
		if ($field = $fields[$template_id]) {

			return $field;
		}
		
		return parent::get_url_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_PostButtons();