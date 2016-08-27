<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTCODE_ADDCONTENTFAQ', PoP_ServerUtils::get_template_definition('postcode-addcontentfaq'));
define ('GD_TEMPLATE_POSTCODE_ACCOUNTFAQ', PoP_ServerUtils::get_template_definition('postcode-accountfaq'));

class GD_Template_Processor_PostCodes extends GD_Template_Processor_PostCodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTCODE_ADDCONTENTFAQ,
			GD_TEMPLATE_POSTCODE_ACCOUNTFAQ,
		);
	}

	function get_post_id($template_id) {

		$post_ids = array(
			GD_TEMPLATE_POSTCODE_ADDCONTENTFAQ => POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ,
			GD_TEMPLATE_POSTCODE_ACCOUNTFAQ => POPTHEME_WASSUP_PAGE_ACCOUNTFAQ,
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
new GD_Template_Processor_PostCodes();