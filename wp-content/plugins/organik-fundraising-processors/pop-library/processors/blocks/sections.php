<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/*********************************************
 * Website Features
 *********************************************/
define ('GD_TEMPLATE_BLOCK_ASKTHEEXPERTS', PoP_ServerUtils::get_template_definition('block-asktheexperts'));
define ('GD_TEMPLATE_BLOCK_HOWMUCHWENEED', PoP_ServerUtils::get_template_definition('block-contact-howmuchweneed'));
define ('GD_TEMPLATE_BLOCK_CONTACTABOUTUS', PoP_ServerUtils::get_template_definition('block-contact-aboutus'));

class OrganikFundraising_Template_Processor_CustomSectionBlocks extends GD_Template_Processor_BlocksBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_ASKTHEEXPERTS,
			GD_TEMPLATE_BLOCK_HOWMUCHWENEED,
			GD_TEMPLATE_BLOCK_CONTACTABOUTUS,
		);
	}

	protected function get_block_inner_templates($template_id) {

		global $gd_template_processor_manager;

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {
			
			/*********************************************
			 * Website Features
			 *********************************************/
			case GD_TEMPLATE_BLOCK_ASKTHEEXPERTS:

				$ret[] = GD_TEMPLATE_POSTCODE_ASKTHEEXPERTS;			
				break;

			case GD_TEMPLATE_BLOCK_HOWMUCHWENEED:

				$ret[] = GD_TEMPLATE_POSTCODE_HOWMUCHWENEED;
				break;

			case GD_TEMPLATE_BLOCK_CONTACTABOUTUS:

				$ret[] = GD_TEMPLATE_POSTCODE_CONTACTABOUTUS;
				break;
		}

		return $ret;
	}

	function get_title($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_ASKTHEEXPERTS:

				return __('Ask the experts!', 'organik-fundraising-processors');
		}
		
		return parent::get_title($template_id);
	}

	// protected function get_title_htmltag($template_id, $atts) {
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_ASKTHEEXPERTS:

	// 			return 'h2';
	// 	}
		
	// 	return parent::get_title_htmltag($template_id, $atts);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OrganikFundraising_Template_Processor_CustomSectionBlocks();