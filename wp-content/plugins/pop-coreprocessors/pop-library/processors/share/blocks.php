<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_EMBED', PoP_ServerUtils::get_template_definition('block-embed'));
define ('GD_TEMPLATE_BLOCK_API', PoP_ServerUtils::get_template_definition('block-api'));
define ('GD_TEMPLATE_BLOCK_COPYSEARCHURL', PoP_ServerUtils::get_template_definition('block-copysearchurl'));

class GD_Template_Processor_ShareBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_EMBED,
			GD_TEMPLATE_BLOCK_API,
			GD_TEMPLATE_BLOCK_COPYSEARCHURL,
		);
	}
	

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_EMBED:

				$ret[] = GD_TEMPLATE_CONTENT_EMBED;
				$ret[] = GD_TEMPLATE_CONTENT_EMBEDPREVIEW;
				break;

			case GD_TEMPLATE_BLOCK_API:

				$ret[] = GD_TEMPLATE_CONTENT_API;
				$ret[] = GD_TEMPLATE_CONTENT_EMBEDPREVIEW;
				break;

			case GD_TEMPLATE_BLOCK_COPYSEARCHURL:

				$ret[] = GD_TEMPLATE_CONTENT_COPYSEARCHURL;
				break;
		}
	
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ShareBlocks();