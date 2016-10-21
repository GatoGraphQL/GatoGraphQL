<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTCODE_CONTACTABOUTUS', PoP_ServerUtils::get_template_definition('postcode-contact-aboutus'));
define ('GD_TEMPLATE_POSTCODE_DEMODOWNLOADS', PoP_ServerUtils::get_template_definition('postcode-demodownloads'));

class GetPoP_Template_Processor_PostCodes extends GD_Template_Processor_PostCodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTCODE_CONTACTABOUTUS,
			GD_TEMPLATE_POSTCODE_DEMODOWNLOADS,
		);
	}

	function get_post_id($template_id) {

		$post_ids = array(
			GD_TEMPLATE_POSTCODE_CONTACTABOUTUS => GETPOP_PROCESSORS_PAGE_CONTACTABOUTUS,
			GD_TEMPLATE_POSTCODE_DEMODOWNLOADS => GETPOP_PROCESSORS_PAGE_DEMODOWNLOADS,
		);
		if ($post_id = $post_ids[$template_id]) {

			return $post_id;
		}
	
		return parent::get_post_id($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_PostCodes();