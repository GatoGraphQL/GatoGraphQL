<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD', PoP_TemplateIDUtils::get_template_definition('blockdata-markallnotificationsasread'));
define ('GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD', PoP_TemplateIDUtils::get_template_definition('blockdata-marknotificationasread'));
define ('GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD', PoP_TemplateIDUtils::get_template_definition('blockdata-marknotificationasunread'));

class GD_AAL_Template_Processor_FunctionsBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD,
			GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD,
			GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD,
		);
	}

	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD:
			case GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD:
			case GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD:

				$actions = array(
					GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD => GD_TEMPLATE_ACTION_MARKALLNOTIFICATIONSASREAD,
					GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD => GD_TEMPLATE_ACTION_MARKNOTIFICATIONASREAD,
					GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD => GD_TEMPLATE_ACTION_MARKNOTIFICATIONASUNREAD,
				);

				foreach ($execution_bag as $pagesection => $pagesection_execution_bag) {
					foreach ($pagesection_execution_bag as $block => $block_execution_bag) {

						// If the block is the previous action, having then taken place
						if ($block == $actions[$template_id]) {

							// It is the AddComment Execution Bag. Get the dataset
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

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$layouts = array(
			GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD => GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASREAD,
			GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD => GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASREAD,
			GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD => GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASUNREAD,
		);
		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}
	
		return $ret;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD:
			case GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD:
			case GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD:

				return GD_DATALOADER_NOTIFICATIONLIST;
		}
		
		return parent::get_dataloader($template_id);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD:
			case GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD:
			case GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD:

				$this->add_att($template_id, $atts, 'data-load', false);	
				$this->append_att($template_id, $atts, 'class', 'hidden');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD:
			case GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD:
			case GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD:

				return GD_DATALOAD_IOHANDLER_LIST;
		}
		
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_FunctionsBlocks();