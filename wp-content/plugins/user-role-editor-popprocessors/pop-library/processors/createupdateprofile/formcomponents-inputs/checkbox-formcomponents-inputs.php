<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// My Preferences
define ('GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY', PoP_TemplateIDUtils::get_template_definition('ure-formcomponent-emailnotifications-network-joinscommunity'));

class GD_URE_Template_Processor_UserProfileCheckboxFormComponentInputs extends GD_Template_Processor_CheckboxFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:

				return __('Joins an organization', 'ure-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {
			
			case GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:

				$fields = array(
					GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => 'pref-emailnotif-network-joinscommunity',
				);

				$ret[] = array('key' => 'value', 'field' => $fields[$template_id]);
				break;
		}

		return $ret;
	}

	function collapsible($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:

				return false;
		}

		return parent::collapsible($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UserProfileCheckboxFormComponentInputs();