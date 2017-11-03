<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE', PoP_TemplateIDUtils::get_template_definition('formcomponent-cup-title'));
define ('GD_TEMPLATE_FORMCOMPONENT_LINK', PoP_TemplateIDUtils::get_template_definition('formcomponent-link'));

class GD_Template_Processor_CreateUpdatePostTextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE,
			GD_TEMPLATE_FORMCOMPONENT_LINK,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE:
				
				return __('Title', 'poptheme-wassup');

			case GD_TEMPLATE_FORMCOMPONENT_LINK:

				return __('Link', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE:
			case GD_TEMPLATE_FORMCOMPONENT_LINK:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE:
				
				$ret[] = array('key' => 'value', 'field' => 'title-edit');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_LINK:
				
				$ret[] = array('key' => 'value', 'field' => 'content-edit');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_LINKACCESS:
				
				$ret[] = array('key' => 'value', 'field' => 'linkaccess');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdatePostTextFormComponentInputs();