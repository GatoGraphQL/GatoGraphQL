<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_EDITOR', PoP_ServerUtils::get_template_definition('formcomponenteditor')); // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)

class GD_Template_Processor_EditorFormComponentInputs extends GD_Template_Processor_EditorFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_EDITOR
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EDITOR:
				
				return __('Content', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EDITOR:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_EditorFormComponentInputs();