<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL', PoP_TemplateIDUtils::get_template_definition('formcomponent-cup-displayemail'));

class GD_Template_Processor_CreateUpdateProfileCheckboxFormComponentInputs extends GD_Template_Processor_CheckboxFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL:

				return __('Show email in your user profile?', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL:

				$ret[] = array('key' => 'value', 'field' => 'display-email');
				break;
		}

		return $ret;
	}

	function collapsible($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL:

				return false;
		}

		return parent::collapsible($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL:

				if (!$this->load_itemobject_value($template_id, $atts)) {

					// For the Creation, set the Display Email by default on Yes
					$this->add_att($template_id, $atts, 'selected', true);
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdateProfileCheckboxFormComponentInputs();