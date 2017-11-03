<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/*--------------------------------------------
 * Details: Thumb, title and excerpt
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS', PoP_TemplateIDUtils::get_template_definition('block-notifications-scroll-details'));

/*--------------------------------------------
 * List
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST', PoP_TemplateIDUtils::get_template_definition('block-notifications-scroll-list'));

class AAL_PoPProcessors_Template_Processor_SectionBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST,
		);
	}

	protected function get_block_inner_templates($template_id) {

		global $gd_template_processor_manager;

		$ret = parent::get_block_inner_templates($template_id);

		$inner_templates = array(
			GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_NOTIFICATIONS_DETAILS,
			GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST => GD_TEMPLATE_SCROLL_NOTIFICATIONS_LIST,
		);

		if ($inner = $inner_templates[$template_id]) {

			$ret[] = $inner;
		}

		return $ret;
	}

	function get_description($template_id, $atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

				// Ask the user to log-in to see the personal notifications
				return sprintf(
					'<div class="visible-notloggedin-anydomain alert alert-sm alert-warning">%s</div>',
					sprintf(
						__('These are the general notifications. Please %s to see your personal notifications.', 'aal-popprocessors'),
						gd_get_login_html()
					)
				);
				break;
		}

		return parent::get_description($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

				// Because different notifications will be given if the user is logged in/out, then
				// refetch the content each time the user logs in or out
				// Need to delete block param 'hist_time' so it can refetch notifications from new users
				// Important: execute these 2 functions in this order! 1st: delete params, 2nd: do the refetch
				$this->add_jsmethod($ret, 'deleteBlockFeedbackValueOnUserLoggedInOut');
				$this->add_jsmethod($ret, 'nonendingRefetchBlockOnUserLoggedInOut');
				
				// Fetch latest notifications every 30 seconds
				$this->add_jsmethod($ret, 'timeoutLoadLatestBlock');

				// User logs in/out, scroll the block to the top
				$this->add_jsmethod($ret, 'scrollTopOnUserLoggedInOut');
				break;
		}
		
		return $ret;
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

				// Needed to set the notifications count on the top bar, bell button
				// if ($target = $this->get_att($template_id, $atts, 'datasetcount-target')) {
				if ($this->get_att($template_id, $atts, 'set-datasetcount')) {

					$ret['datasetcount-target'] = '#'.AAL_PoPProcessors_NotificationUtils::get_notificationcount_id();//$target;
					$ret['datasetcount-updatetitle'] = true;
				}

				// Only fetch time and again if the user is logged in
				$ret['only-loggedin'] = true;

				// Params to delete for function deleteBlockFeedbackValueOnUserLoggedInOut
				// Array of arrays: many params to delete, multiple levels down for each
				$ret['user:loggedinout-deletefeedbackvalue'] = array(
					array(
						GD_DATALOAD_PARAMS, 'hist_time'
					)
				);
				break;
		}

		return $ret;
	}

	protected function show_fetchmore($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
			
				return true;
		}

		return parent::show_fetchmore($template_id);
	}

	function get_dataload_source($template_id, $atts) {

		global $gd_template_settingsmanager;
		
		$ret = parent::get_dataload_source($template_id, $atts);

		// Add the format attr
		$details = array(
			GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST,
		);
		if (in_array($template_id, $details)) {
			
			$format = GD_TEMPLATEFORMAT_DETAILS;
		}
		elseif (in_array($template_id, $lists)) {
			
			$format = GD_TEMPLATEFORMAT_LIST;
		}
		
		if ($format) {

			$ret = add_query_arg(GD_URLPARAM_FORMAT, $format, $ret);
		}
	
		return $ret;
	}

	// protected function get_block_page($template_id) {

	// 	global $gd_template_settingsmanager;

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
	// 		case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

	// 			// Make explicit that it is "page" hierarchy, since the Notifications in the Top pageSection can be loaded initially from any hierarchy, so if not explicit it won't find the block here
	// 			if ($page = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_PAGE)) {

	// 				return $page;
	// 			}
	// 			break;
	// 	}

	// 	return parent::get_block_page($template_id);
	// }

	protected function get_dataload_query_args($template_id, $atts) {

		$ret = parent::get_dataload_query_args($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

				// Limit: 2 times the default for posts
				$notifications_query_args = array(
					'limit' => get_option('posts_per_page') * 2,
				);
				
				$ret = array_merge(
					$ret,
					$notifications_query_args
				);
				break;
		}

		return $ret;
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

				// return GD_TEMPLATE_CONTROLGROUP_BLOCKRELOAD;
				return AAL_POPPROCESSORS_TEMPLATE_CONTROLGROUP_NOTIFICATIONLIST;

		}

		return parent::get_controlgroup_top($template_id);
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

				return GD_TEMPLATE_MESSAGEFEEDBACK_NOTIFICATIONS;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_messagefeedback_position($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
			
				return 'bottom';
		}

		return parent::get_messagefeedback_position($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
			
				// return GD_DATALOAD_IOHANDLER_STATEFULNOTIFICATIONLIST;
				return GD_DATALOAD_IOHANDLER_NOTIFICATIONLIST;
		}
		
		return parent::get_iohandler($template_id);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

				return GD_DATALOADER_NOTIFICATIONLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		// Set the display configuration
		$details = array(
			GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST,
		);
		
		// Important: set always this value, because the IOHandler used by all different blocks is the same!
		// So if not restarting, the display will be the same as the previous one, and sometimes it doesn't need the display
		// (Eg: tables)
		if (in_array($template_id, $details)) {
			
			$format = GD_TEMPLATEFORMAT_DETAILS;
		}
		elseif (in_array($template_id, $lists)) {
			
			$format = GD_TEMPLATEFORMAT_LIST;
		}

		if ($format) {
			$ret['iohandler-atts'][GD_URLPARAM_FORMAT] = $format;
		}

		return $ret;
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:

				$this->append_att($template_id, $atts, 'class', 'block-notifications');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors_Template_Processor_SectionBlocks();