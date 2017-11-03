<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL', PoP_TemplateIDUtils::get_template_definition('copysearchurl'));
define ('GD_TEMPLATE_FORMCOMPONENT_API', PoP_TemplateIDUtils::get_template_definition('api'));

class GD_Template_Processor_ShareTextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL,
			GD_TEMPLATE_FORMCOMPONENT_API,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL:

				return __('Copy Search URL', 'pop-coreprocessors');
				
			case GD_TEMPLATE_FORMCOMPONENT_API:

				return __('Copy URL', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL:
			case GD_TEMPLATE_FORMCOMPONENT_API:

				// Because the method depends on modal.on('shown.bs.modal'), we need to run it before the modal is open for the first time
				// (when it would initialize the JS, so then this first execution would be lost otherwise)
				$this->add_jsmethod($ret, 'replaceCode');
				break;
		}

		return $ret;
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_FORMCOMPONENT_API:
			
				// Needed for JS method `replaceCode`
				$ret['url-type'] = 'api';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL:
			case GD_TEMPLATE_FORMCOMPONENT_API:
			
				$this->merge_att($template_id, $atts, 'params', array(
					'data-code-placeholder' => '{0}'
				));
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ShareTextFormComponentInputs();