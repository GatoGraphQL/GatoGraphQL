<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FilterInnersBase extends GD_Template_Processor_FormInnersBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_filter_object($template_id) {

		if ($filter = $this->get_filter($template_id)) {

			global $gd_filter_manager;
			return $gd_filter_manager->get_filter($filter);
		}

		return null;
	}

	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id, $atts);

		// All filtercomponents
		if ($filter_object = $this->get_filter_object($template_id)) {

			foreach ($filter_object->get_filtercomponents() as $filtercomponent) {
			
				// Allow to either get either the filter's formcomponent/filterformcomponent
				$ret[] = $this->get_filter_component($template_id, $filtercomponent);
			}
			if ($submitbtn = $this->get_submitbtn_template($template_id)) {
				
				$ret[] = $submitbtn;
			}
		
			// Add the hidden input with the name of the filter
			$ret[] = GD_TEMPLATE_FORMCOMPONENT_FILTERNAME;
		}
		
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
				
		$ret = parent::get_template_configuration($template_id, $atts);			

		// Add more Attributes
		$filter_object = $atts['filter-object'];
		$ret['filter-name'] = $filter_object->get_name();
		$ret['input-class'] = GD_FILTER_NAME_INPUT;
		$ret['filtering-field'] = GD_FILTER_FILTERING_FIELD;				
		
		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	// Add class `GD_FILTER_NAME_INPUT` to all filtercomponents
	// 	if ($filter_object = $atts['filter-object']) {

	// 		foreach ($filter_object->get_filtercomponents() as $filtercomponent) {
			
	// 			$this->append_att($filtercomponent->get_filterformcomponent(), $atts, 'class', GD_FILTER_NAME_INPUT);
	// 		}
	// 	}

	// 	// if ($input_class = $this->get_att($template_id, $atts, 'input-class')) {
			
	// 	// 	foreach ($this->get_layouts($template_id, $atts) as $layout) {
			
	// 	// 		$this->append_att($layout, $atts, 'class', $input_class);				
	// 	// 	}
	// 	// }

	// 	return parent::init_atts($template_id, $atts);
	// }

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	function get_filter_component($template_id, $filtercomponent) {

		// By default get the formcomponent. This can be overriden to get the get_filterformcomponent for a simpler filter, as used in the sideinfo.
		return $filtercomponent->get_formcomponent();
	}
	
	function get_filter($template_id) {

		return null;
	}

	function get_submitbtn_template($template_id) {

		return GD_TEMPLATE_SUBMITBUTTONFORMGROUP_SEARCH;
	}	
}
