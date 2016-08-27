<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTON_HIGHLIGHTEDIT', PoP_ServerUtils::get_template_definition('button-highlightedit'));
define ('GD_TEMPLATE_BUTTON_HIGHLIGHTVIEW', PoP_ServerUtils::get_template_definition('button-highlightview'));
define ('GD_TEMPLATE_BUTTON_ADDONSPOSTEDIT', PoP_ServerUtils::get_template_definition('button-addonspostedit'));
define ('GD_TEMPLATE_BUTTON_ADDONSORMAINPOSTEDIT', PoP_ServerUtils::get_template_definition('button-addonsormainpostedit'));

class Wassup_Template_Processor_Buttons extends GD_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTON_HIGHLIGHTEDIT,
			GD_TEMPLATE_BUTTON_HIGHLIGHTVIEW,
			GD_TEMPLATE_BUTTON_ADDONSPOSTEDIT,
			GD_TEMPLATE_BUTTON_ADDONSORMAINPOSTEDIT,
		);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_BUTTON_HIGHLIGHTEDIT => GD_TEMPLATE_BUTTONINNER_POSTEDIT,
			GD_TEMPLATE_BUTTON_HIGHLIGHTVIEW => GD_TEMPLATE_BUTTONINNER_POSTVIEW,
			GD_TEMPLATE_BUTTON_ADDONSPOSTEDIT => GD_TEMPLATE_BUTTONINNER_POSTEDIT,
			GD_TEMPLATE_BUTTON_ADDONSORMAINPOSTEDIT => GD_TEMPLATE_BUTTONINNER_POSTEDIT,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_url_field($template_id) {
		
		$fields = array(
			GD_TEMPLATE_BUTTON_HIGHLIGHTEDIT => 'edit-url',
			GD_TEMPLATE_BUTTON_HIGHLIGHTVIEW => 'referenced-url',
			GD_TEMPLATE_BUTTON_ADDONSPOSTEDIT => 'edit-url',
			GD_TEMPLATE_BUTTON_ADDONSORMAINPOSTEDIT => 'edit-url',
		);
		if ($field = $fields[$template_id]) {

			return $field;
		}

		return parent::get_url_field($template_id);
	}

	function get_title($template_id) {
		
		$titles = array(
			GD_TEMPLATE_BUTTON_HIGHLIGHTEDIT => __('Edit', 'poptheme-wassup'),
			GD_TEMPLATE_BUTTON_HIGHLIGHTVIEW => __('View', 'poptheme-wassup'),
			GD_TEMPLATE_BUTTON_ADDONSPOSTEDIT => __('Edit', 'poptheme-wassup'),
			GD_TEMPLATE_BUTTON_ADDONSORMAINPOSTEDIT => __('Edit', 'poptheme-wassup'),
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_title($template_id);
	}

	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_HIGHLIGHTEDIT;
			case GD_TEMPLATE_BUTTON_ADDONSPOSTEDIT:

				return GD_URLPARAM_TARGET_ADDONS;

			case GD_TEMPLATE_BUTTON_ADDONSORMAINPOSTEDIT:

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

			case GD_TEMPLATE_BUTTON_HIGHLIGHTVIEW:
			case GD_TEMPLATE_BUTTON_HIGHLIGHTEDIT:
			case GD_TEMPLATE_BUTTON_ADDONSPOSTEDIT:
			case GD_TEMPLATE_BUTTON_ADDONSORMAINPOSTEDIT:

				$ret .= ' btn btn-xs btn-default';
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_Buttons();