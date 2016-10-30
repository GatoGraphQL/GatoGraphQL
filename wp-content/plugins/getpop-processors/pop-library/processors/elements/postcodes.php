<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTCODE_CONTACTABOUTUS', PoP_ServerUtils::get_template_definition('postcode-contact-aboutus'));
define ('GD_TEMPLATE_POSTCODE_WHATISIT', PoP_ServerUtils::get_template_definition('postcode-whatisit'));
// define ('GD_TEMPLATE_POSTCODE_DISCOVER', PoP_ServerUtils::get_template_definition('postcode-discover'));
define ('GD_TEMPLATE_POSTCODE_FRAMEWORK', PoP_ServerUtils::get_template_definition('postcode-framework'));

class GetPoP_Template_Processor_PostCodes extends GD_Template_Processor_PostCodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTCODE_CONTACTABOUTUS,
			GD_TEMPLATE_POSTCODE_WHATISIT,
			// GD_TEMPLATE_POSTCODE_DISCOVER,
			GD_TEMPLATE_POSTCODE_FRAMEWORK,
		);
	}

	function get_post_id($template_id) {

		$post_ids = array(
			GD_TEMPLATE_POSTCODE_CONTACTABOUTUS => GETPOP_PROCESSORS_PAGE_CONTACTABOUTUS,
			GD_TEMPLATE_POSTCODE_WHATISIT => GETPOP_PROCESSORS_PAGE_WHATISIT,
			// GD_TEMPLATE_POSTCODE_DISCOVER => GETPOP_PROCESSORS_PAGE_DISCOVER,
			GD_TEMPLATE_POSTCODE_FRAMEWORK => GETPOP_PROCESSORS_PAGE_FRAMEWORK,
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