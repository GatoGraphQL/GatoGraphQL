<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER', PoP_ServerUtils::get_template_definition('formcomponent-selectabletypeaheadtrigger-profiles'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER', PoP_ServerUtils::get_template_definition('selectableprofiles', true));

class GD_Template_Processor_UserSelectableTypeaheadTriggerFormComponentInputs extends GD_Template_Processor_UserSelectableTypeaheadTriggerFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER,
			GD_TEMPLATE_FILTERFORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER
		);
	}

	function get_selected_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER:

				return GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED;
				
			case GD_TEMPLATE_FILTERFORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER:

				return GD_TEMPLATE_LAYOUTUSER_FILTERTYPEAHEAD_SELECTED;
		}

		return parent::get_selected_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserSelectableTypeaheadTriggerFormComponentInputs();