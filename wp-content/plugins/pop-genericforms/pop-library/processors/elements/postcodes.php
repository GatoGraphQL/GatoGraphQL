<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_PAGECODE_NEWSLETTER', PoP_TemplateIDUtils::get_template_definition('pagecode-newsletter'));

class GenericForms_Template_Processor_PostCodes extends GD_Template_Processor_PostCodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_PAGECODE_NEWSLETTER,
		);
	}

	function get_post_id($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGECODE_NEWSLETTER:

				return POP_GENERICFORMS_PAGE_NEWSLETTER;
		}
	
		return parent::get_post_id($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GenericForms_Template_Processor_PostCodes();