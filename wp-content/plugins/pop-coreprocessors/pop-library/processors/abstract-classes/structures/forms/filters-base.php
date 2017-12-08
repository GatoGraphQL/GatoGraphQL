<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SUBMITFORMTYPE_FILTERBLOCK', 'filterblock');
define ('GD_SUBMITFORMTYPE_FILTERBLOCKGROUP', 'filterblockgroup');

class GD_Template_Processor_FiltersBase extends GD_Template_Processor_FormsBase {

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'initFilter');

		// Depending on the form type, execute a js method or another
		$form_type = $this->get_form_type($template_id, $atts);
		if ($form_type == GD_SUBMITFORMTYPE_FILTERBLOCK) {

			$this->add_jsmethod($ret, 'initBlockFilter');
		}
		elseif ($form_type == GD_SUBMITFORMTYPE_FILTERBLOCKGROUP) {

			$this->add_jsmethod($ret, 'initBlockGroupFilter');
		}
		return $ret;
	}

	function get_form_type($template_id, $atts) {

		// Allow the Block to set the form type (eg: to override FILTERBLOCK with FILTERBLOCKGROUP)
		if ($form_type = $this->get_att($template_id, $atts, 'form-type')) {

			return $form_type;
		}

		// Default: filter the block
		return GD_SUBMITFORMTYPE_FILTERBLOCK;
	}

	function get_method($template_id) {

		// Comment Leo 08/12/2017: if we have no JS, then use 'POST', because otherwise the ?config=disable-js parameter
		// gets dropped away and the search response is, once again, JS-enabled
		// Source: https://stackoverflow.com/questions/732371/what-happens-if-the-action-field-in-a-form-has-parameters
		if (PoP_Frontend_ServerUtils::disable_js()) {

			return 'POST';
		}

		return 'GET';
	}
	
	function fixed_id($template_id, $atts) {

		// So that it can be collapsed from the ControlGroup
		return true;
	}

	// function get_filter_object($template_id) {

	// 	$filterinner = $this->get_inner_template($template_id);

	// 	global $gd_template_processor_manager;
	// 	return $gd_template_processor_manager->get_processor($filterinner)->get_filter_object($filterinner);
	// }
	// function get_filter_object($template_id) {

	// 	$filterinner = $this->get_inner_template($template_id);

	// 	global $gd_template_processor_manager, $gd_filter_manager;
	// 	if ($filtername = $gd_template_processor_manager->get_processor($filterinner)->get_filter($filterinner)) {

	// 		return $gd_filter_manager->get_filter($filtername);
	// 	}

	// 	return null;
	// }
	function get_filter_object($template_id) {

		$filterinner = $this->get_inner_template($template_id);

		global $gd_template_processor_manager, $gd_filter_manager;
		return $gd_template_processor_manager->get_processor($filterinner)->get_filter_object($filterinner);
	}
}
