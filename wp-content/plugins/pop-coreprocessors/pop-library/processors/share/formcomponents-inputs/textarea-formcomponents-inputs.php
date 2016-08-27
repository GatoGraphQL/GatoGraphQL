<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_EMBEDCODE', PoP_ServerUtils::get_template_definition('embedcode'));

class GD_Template_Processor_ShareTextareaFormComponentInputs extends GD_Template_Processor_TextareaFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_EMBEDCODE,
			// GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_EMBEDCODE:
				
				return __('Embed code', 'pop-coreprocessors');

			// case GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL:

			// 	return __('Copy Search URL', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_FORMCOMPONENT_EMBEDCODE:
			// case GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL:

				// Because the method depends on modal.on('shown.bs.modal'), we need to run it before the modal is open for the first time
				// (when it would initialize the JS, so then this first execution would be lost otherwise)
				$this->add_jsmethod($ret, 'embedCode');
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_EMBEDCODE:
			
				$placeholder = '<iframe width="100%" height="500" src="{0}" frameborder="0" allowfullscreen="true"></iframe>';
				$this->merge_att($template_id, $atts, 'params', array(
					'data-code-placeholder' => $placeholder
				));
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ShareTextareaFormComponentInputs();