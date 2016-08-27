<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_ADDCONTENTFAQ', PoP_ServerUtils::get_template_definition('block-addcontentfaq'));
define ('GD_TEMPLATE_BLOCK_ACCOUNTFAQ', PoP_ServerUtils::get_template_definition('block-accountfaq'));
define ('GD_TEMPLATE_BLOCK_OURSPONSORSINTRO', PoP_ServerUtils::get_template_definition('block-oursponsorsintro'));
define ('GD_TEMPLATE_BLOCK_HOMEWELCOME', PoP_ServerUtils::get_template_definition('block-homewelcome'));

class GD_Template_Processor_CustomBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_ADDCONTENTFAQ,
			GD_TEMPLATE_BLOCK_ACCOUNTFAQ,
			GD_TEMPLATE_BLOCK_OURSPONSORSINTRO,
			GD_TEMPLATE_BLOCK_HOMEWELCOME,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_ADDCONTENTFAQ:

				$ret[] = GD_TEMPLATE_POSTCODE_ADDCONTENTFAQ;
				break;

			case GD_TEMPLATE_BLOCK_ACCOUNTFAQ:

				$ret[] = GD_TEMPLATE_POSTCODE_ACCOUNTFAQ;
				break;

			case GD_TEMPLATE_BLOCK_OURSPONSORSINTRO:

				$ret[] = apply_filters('GD_Template_Processor_CustomBlocks:block-inners:oursponsorsintro', GD_TEMPLATE_CODE_OURSPONSORSINTRO);
				break;

			case GD_TEMPLATE_BLOCK_HOMEWELCOME:

				if ($inner = apply_filters('GD_Template_Processor_CustomBlocks:block-inners:homewelcome', GD_TEMPLATE_CODE_HOMEWELCOME)) {
					$ret[] = $inner;
				}
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_HOMEWELCOME:

				$this->append_att($template_id, $atts, 'class', 'block-homewelcome');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	// function get_title($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_ADDCONTENTFAQ:

	// 			return sprintf(
	// 				__('FAQ: Adding Content in %s', 'poptheme-wassup'),
	// 				get_bloginfo('name')
	// 			);

	// 		case GD_TEMPLATE_BLOCK_ACCOUNTFAQ:

	// 			return sprintf(
	// 				__('FAQ: Registering in %s', 'poptheme-wassup'),
	// 				get_bloginfo('name')
	// 			);
	// 	}
	
	// 	return parent::get_title($template_id);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomBlocks();