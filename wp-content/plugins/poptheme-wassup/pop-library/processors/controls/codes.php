<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL', PoP_TemplateIDUtils::get_template_definition('code-updownvoteundoupdownvotepost-label'));

class GD_Template_Processor_CustomCodes extends GD_Template_Processor_CodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL,
		);
	}

	function get_code($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL:

				// Allow TPP Debate website to override this label with "Agree?"
				$labels = array(
					GD_TEMPLATE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL => apply_filters('GD_Template_Processor_CustomCodes:updownvote:label', __('Important?', 'poptheme-wassup')),
				);

				return sprintf(
					'<span class="btn btn-link btn-compact btn-span pop-functionbutton">%s</span>',
					$labels[$template_id]
				);
		}
	
		return parent::get_code($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {
		
		switch ($template_id) {
					
			case GD_TEMPLATE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL:

				// Artificial property added to identify the template when adding template-resources
				$this->add_att($template_id, $atts, 'resourceloader', 'functionbutton');
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomCodes();