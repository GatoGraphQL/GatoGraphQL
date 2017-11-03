<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS', PoP_TemplateIDUtils::get_template_definition('aal-block-latestnotifications'));
define ('GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS', PoP_TemplateIDUtils::get_template_definition('aal-blockdata-latestnotifications'));

class AAL_PoPProcessors_Template_Processor_Blocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS,
			GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS,
		);
	}

	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS:

				return GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS;
		}

		return parent::get_settings_id($template_id);
	}
	
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {

			// Display the dataset also when the block triggers event 'rendered', meaning
			// to do if after the user has logged in with the hover login block
			case GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS:
				$ret['display-datasetcount-when'] = array(
					'oncreated',
					'onrendered',
				);
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS:
			case GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS:

				$ret['datasetcount-updatetitle'] = true;
				break;
		}

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS:
			case GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS:
				$this->add_jsmethod($ret, 'displayBlockDatasetCount');
				break;
		}
		
		return $ret;
	}

	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS:

				// If the user is logged in, the GD_TEMPLATE_ACTION_LOGIN was successful, then load the data
				$vars = GD_TemplateManager_Utils::get_vars();
				if ($vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {

					$data_settings[GD_DATALOAD_LOAD] = true;
				}
				break;
		}

		return parent::integrate_execution_bag($template_id, $atts, $data_settings, $execution_bag);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS:

				return GD_DATALOADER_NONOTIFICATIONS;

			case GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS:

				// return GD_DATALOADER_USERLATESTNOTIFICATIONS;
				return GD_DATALOADER_NOTIFICATIONLIST;
		}
		
		return parent::get_dataloader($template_id);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS:

				$this->add_att($template_id, $atts, 'data-load', false);	
				$this->append_att($template_id, $atts, 'class', 'hidden');	
				break;
		}

		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS:
			case GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-datasetcount-target' => '#'.AAL_PoPProcessors_NotificationUtils::get_notificationcount_id(),//GD_TEMPLATE_ID_NOTIFICATIONSCOUNT,
				));	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCK_LATESTNOTIFICATIONS:
			case GD_TEMPLATE_BLOCKDATA_LATESTNOTIFICATIONS:

				// return GD_DATALOAD_IOHANDLER_STATEFULLATESTNOTIFICATIONLIST;
				return GD_DATALOAD_IOHANDLER_LATESTNOTIFICATIONLIST;
		}
		
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors_Template_Processor_Blocks();