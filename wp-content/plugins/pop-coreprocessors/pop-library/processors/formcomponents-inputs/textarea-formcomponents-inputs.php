<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR', PoP_ServerUtils::get_template_definition('formcomponent-textarea-editor'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILS', PoP_ServerUtils::get_template_definition('formcomponent-emails'));
define ('GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE', PoP_ServerUtils::get_template_definition('formcomponent-additionalmessage'));

class GD_Template_Processor_TextareaFormComponentInputs extends GD_Template_Processor_TextareaFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR,
			GD_TEMPLATE_FORMCOMPONENT_EMAILS,
			GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE,
		);
	}

	function get_rows($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR:
				
				return 5;
		}

		return parent::get_rows($template_id, $atts);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR:
				
				return __('Content', 'pop-coreprocessors');
				
			case GD_TEMPLATE_FORMCOMPONENT_EMAILS:
				
				return __('Email(s)', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE:

				return __('Additional message', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILS:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR:
				
				$ret[] = array('key' => 'value', 'field' => 'content-edit');
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EMAILS:

				$this->add_att($template_id, $atts, 'pop-form-clear', true);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TextareaFormComponentInputs();