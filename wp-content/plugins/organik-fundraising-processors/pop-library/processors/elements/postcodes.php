<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTCODE_ASKTHEEXPERTS', PoP_ServerUtils::get_template_definition('postcode-asktheexperts'));
define ('GD_TEMPLATE_POSTCODE_HOWMUCHWENEED', PoP_ServerUtils::get_template_definition('postcode-howmuchweneed'));
define ('GD_TEMPLATE_POSTCODE_CONTACTABOUTUS', PoP_ServerUtils::get_template_definition('postcode-contactaboutus'));

class OrganikFundraising_Template_Processor_PostCodes extends GD_Template_Processor_PostCodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTCODE_ASKTHEEXPERTS,
			GD_TEMPLATE_POSTCODE_HOWMUCHWENEED,
			GD_TEMPLATE_POSTCODE_CONTACTABOUTUS,
		);
	}

	function get_post_id($template_id) {

		$post_ids = array(
			GD_TEMPLATE_POSTCODE_ASKTHEEXPERTS => ORGANIKFUNDRAISING_PROCESSORS_PAGE_ASKTHEEXPERTS,
			GD_TEMPLATE_POSTCODE_HOWMUCHWENEED => ORGANIKFUNDRAISING_PROCESSORS_PAGE_HOWMUCHWENEED,
			GD_TEMPLATE_POSTCODE_CONTACTABOUTUS => ORGANIKFUNDRAISING_PROCESSORS_PAGE_CONTACTABOUTUS,
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
new OrganikFundraising_Template_Processor_PostCodes();