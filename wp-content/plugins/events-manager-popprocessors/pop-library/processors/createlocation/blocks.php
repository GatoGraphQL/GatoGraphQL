<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_CREATELOCATION', PoP_ServerUtils::get_template_definition('block-em-createlocation'));
define ('GD_TEMPLATE_BLOCKDATA_CREATELOCATION', PoP_ServerUtils::get_template_definition('blockdata-em-createlocation'));

class GD_EM_Template_Processor_CreateLocationBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_CREATELOCATION,
			// GD_TEMPLATE_ACTION_CREATELOCATION,
			GD_TEMPLATE_BLOCKDATA_CREATELOCATION,
		);
	}
	
	// protected function get_iohandler($template_id) {
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_ACTION_CREATELOCATION:

	// 			return GD_DATALOAD_IOHANDLER_FORM;
	// 	}

	// 	return parent::get_iohandler($template_id);
	// }

	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_CREATELOCATION:

				foreach ($execution_bag as $pagesection => $pagesection_execution_bag) {
					foreach ($pagesection_execution_bag as $block => $block_execution_bag) {

						if ($block == GD_TEMPLATE_ACTION_CREATELOCATION) {

							if ($dataset = $block_execution_bag['dataset']) {
								$data_settings['dataload-atts']['include'] = $dataset;
								$data_settings[GD_DATALOAD_LOAD] = true;
							}
							break;
						}						
					}
				}
				break;
		}

		return parent::integrate_execution_bag($template_id, $atts, $data_settings, $execution_bag);
	}

	// protected function get_actionexecuter($template_id) {
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_ACTION_CREATELOCATION:

	// 			return GD_DATALOAD_ACTIONEXECUTER_CREATELOCATION;
	// 	}

	// 	return parent::get_actionexecuter($template_id);
	// }

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_CREATELOCATION:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_CREATELOCATION;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_CREATELOCATION:

				$ret[] = GD_TEMPLATE_FRAME_CREATELOCATIONMAP;
				break;

			case GD_TEMPLATE_BLOCKDATA_CREATELOCATION:

				$ret[] = GD_TEMPLATE_TRIGGERTYPEAHEADSELECT_LOCATION;
				break;
		}
	
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_CREATELOCATION:

				$this->add_att($template_id, $atts, 'data-load', false);	
				$this->append_att($template_id, $atts, 'class', 'hidden');	
				break;

			case GD_TEMPLATE_BLOCK_CREATELOCATION:

				// Change the 'Loading' message in the Status
				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Adding Location...', 'em-popprocessors'));	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_CREATELOCATION:

				return GD_DATALOADER_LOCATIONLIST;

			// case GD_TEMPLATE_ACTION_CREATELOCATION:

			// 	return GD_DATALOADER_DELEGATE;
		}

		return parent::get_dataloader($template_id);
	}

	// function get_settings_id($template_id) {
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_ACTION_CREATELOCATION:

	// 			return GD_TEMPLATE_BLOCK_CREATELOCATION;
	// 	}

	// 	return parent::get_settings_id($template_id);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CreateLocationBlocks();