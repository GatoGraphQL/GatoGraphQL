<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR', PoP_TemplateIDUtils::get_template_definition('formcomponentcommenteditor')); // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)

class GD_Template_Processor_CommentEditorFormComponentInputs extends GD_Template_Processor_EditorFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR
		);
	}

	// function get_rows($template_id, $atts) {
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR:
				
	// 			return 3;
	// 	}
		
	// 	return parent::get_rows($template_id, $atts);
	// }

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR:

				$this->append_att($template_id, $atts, 'class', 'pop-editor-form-clear');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR:
				
				return __('Comment*', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	// function get_block_jsmethod($template_id, $atts) {

	// 	$ret = parent::get_block_jsmethod($template_id, $atts);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR:

	// 			$this->add_jsmethod($ret, 'editorFocus');
	// 			break;
	// 	}
	// 	return $ret;
	// }

	function autofocus($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR:

				return true;
		}
		
		return parent::autofocus($template_id, $atts);
	}

	// function get_placeholder($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR:
				
	// 			return __('Type comment here...', 'pop-coreprocessors');
	// 	}
		
	// 	return parent::get_placeholder($template_id, $atts);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentEditorFormComponentInputs();