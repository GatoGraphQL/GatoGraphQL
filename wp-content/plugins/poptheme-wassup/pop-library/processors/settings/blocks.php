<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_SETTINGS', PoP_ServerUtils::get_template_definition('block-settings'));

class GD_Template_Processor_CustomSettingsBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_SETTINGS,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SETTINGS:

				$ret[] = GD_TEMPLATE_FORM_SETTINGS;
				break;
		}

		return $ret;
	}

	protected function get_messagefeedback($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SETTINGS:

				return GD_TEMPLATE_MESSAGEFEEDBACK_SETTINGS;
		}

		return parent::get_messagefeedback($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomSettingsBlocks();