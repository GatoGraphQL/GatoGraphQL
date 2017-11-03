<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_WSL_TEMPLATE_BLOCK_NETWORKLINKS', PoP_TemplateIDUtils::get_template_definition('wsl-block-networklinks'));

class GD_WSL_Template_Processor_NetworkLinkBlocks extends GD_WSL_Template_Processor_NetworkLinkBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_WSL_TEMPLATE_BLOCK_NETWORKLINKS
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_WSL_TEMPLATE_BLOCK_NETWORKLINKS:

				// $ret[] = GD_TEMPLATE_DIVIDER;
				$ret[] = GD_TEMPLATE_WSL_NETWORKLINKS;
				break;
		}
	
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_WSL_Template_Processor_NetworkLinkBlocks();