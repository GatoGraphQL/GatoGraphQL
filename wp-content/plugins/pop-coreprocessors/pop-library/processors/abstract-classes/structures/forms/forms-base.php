<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SUBMITFORMTYPE_FETCHBLOCK', 'fetchblock');

class GD_Template_Processor_FormsBase extends GD_Template_Processor_StructuresBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_FORM;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		$form_type = $this->get_form_type($template_id, $atts);
		if ($form_type == GD_SUBMITFORMTYPE_FETCHBLOCK) {
			$this->add_jsmethod($ret, 'forms');
		}
		
		if ($this->get_att($template_id, $atts, 'intercept-action-url')) {
			$this->add_jsmethod($ret, 'interceptForm', 'interceptor');
		}
		return $ret;
	}

	function get_form_type($template_id, $atts) {

		return GD_SUBMITFORMTYPE_FETCHBLOCK;
	}

	function get_method($template_id) {

		return 'POST';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['method'] = $this->get_method($template_id);
		if ($description = $this->get_att($template_id, $atts, 'description')) {

			$ret[GD_JS_DESCRIPTION/*'description'*/] = $description;
		}
		if ($description_bottom = $this->get_att($template_id, $atts, 'description-bottom')) {

			$ret['description-bottom'] = $description_bottom;
		}

		return $ret;
	}

	function get_intercept_urls($template_id, $atts) {

		$ret = parent::get_intercept_urls($template_id, $atts);
	
		if ($intercept_action_url = $this->get_att($template_id, $atts, 'intercept-action-url')) {
			
			// If intercepting the filter's action URL, must also add the filter name
			global $gd_filter_manager;
			$filter_object = $atts['filter-object'];
			$intercept_url = $gd_filter_manager->add_filter_params($intercept_action_url, $filter_object);
			$ret[$template_id] = $intercept_url;
		}

		return $ret;
	}
	// function get_intercept_settings($template_id, $atts) {

	// 	$ret = parent::get_intercept_settings($template_id, $atts);
	
	// 	if ($this->get_att($template_id, $atts, 'intercept-action-url')) {
			
	// 		$ret[] = GD_INTERCEPTOR_WITHPARAMS;
	// 	}

	// 	return $ret;
	// }
	function get_intercept_type($template_id, $atts) {

		if ($this->get_att($template_id, $atts, 'intercept-action-url')) {
			
			return 'partialurl';
		}

		return parent::get_intercept_type($template_id, $atts);
	}
}
