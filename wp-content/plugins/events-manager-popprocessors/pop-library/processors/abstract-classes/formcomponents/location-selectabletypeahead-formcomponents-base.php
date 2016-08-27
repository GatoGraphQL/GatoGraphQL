<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_LocationSelectableTypeaheadFormComponentsBase extends GD_Template_Processor_PostSelectableTypeaheadFormComponentsBase {

	function get_selected_layout_template($template_id) {

		return GD_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_SELECTED;
	}

	function get_dataloader($template_id) {

		return GD_DATALOADER_LOCATIONLIST;
	}

	function get_input_template($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION;
	}

	// function get_suggestion_layout($template_id) {

	// 	return GD_TEMPLATE_EM_LAYOUT_LOCATIONNAME;
	// }
	function get_suggestion_fontawesome($template_id, $atts) {

		return 'fa-fw fa-map-marker';
	}
	
	function fixed_id($template_id, $atts) {

		return true;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		// Set the typeahead id on all the controls from the input template
		$input = $this->get_input_template($template_id);
		$controls = $gd_template_processor_manager->get_processor($input)->get_controls($input);
		foreach ($controls as $control) {

			$this->merge_att($control, $atts, 'previoustemplates-ids', array(
				'data-typeahead-target' => $template_id,
			));
		}

		return parent::init_atts($template_id, $atts);
	}
}
