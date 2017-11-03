<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_UPDATE', PoP_TemplateIDUtils::get_template_definition('block-profileorganization-update'));
define ('GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_UPDATE', PoP_TemplateIDUtils::get_template_definition('block-profileindividual-update'));

class GD_URE_Template_Processor_UpdateProfileBlocks extends GD_Template_Processor_UpdateProfileBlocksBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_UPDATE,
			GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_UPDATE,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_UPDATE:

				$ret[] = GD_TEMPLATE_FORM_PROFILEORGANIZATION_UPDATE;
				break;

			case GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_UPDATE:

				$ret[] = GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_UPDATE;
				break;
		}
	
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UpdateProfileBlocks();