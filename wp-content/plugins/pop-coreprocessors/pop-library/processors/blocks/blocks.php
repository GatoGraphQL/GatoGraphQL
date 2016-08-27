<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_INVITENEWUSERS', PoP_ServerUtils::get_template_definition('block-inviteusers'));
define ('GD_TEMPLATE_BLOCK_LATESTCOUNTS', PoP_ServerUtils::get_template_definition('block-latestcounts'));
define ('GD_TEMPLATE_BLOCK_EXTERNAL', PoP_ServerUtils::get_template_definition('block-external'));

class GD_Core_Template_Processor_Blocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_BLOCK_INVITENEWUSERS,
			GD_TEMPLATE_BLOCK_LATESTCOUNTS,
			GD_TEMPLATE_BLOCK_EXTERNAL,
		);
	}

	protected function get_description($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_INVITENEWUSERS:

				// Allow Organik Fundraising to override it, changing the title to "Share by email"
				return apply_filters(
					'GD_Core_Template_Processor_Blocks:inviteusers:description',
					sprintf(
						'<p>%s</p>',
						sprintf(
							__('Send an invite to your friends/colleagues/etc to join <em><strong>%s</strong></em>:', 'pop-coreprocessors'),
							get_bloginfo('name')
						)
					)
				);
		}
		
		return parent::get_description($template_id, $atts);
	}

	// function get_title($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_EXTERNAL:

	// 			// Use the name of the website, so that when reloading this page on GetPoP.org, on the top navigation bar it show "GetPoP" instead of "External"
	// 			return get_bloginfo('name');
	// 	}
		
	// 	return parent::get_title($template_id);
	// }

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$inner_templates = array(
			GD_TEMPLATE_BLOCK_INVITENEWUSERS => GD_TEMPLATE_FORM_INVITENEWUSERS,
			GD_TEMPLATE_BLOCK_LATESTCOUNTS => GD_TEMPLATE_CONTENT_LATESTCOUNTS,
		);

		if ($inner = $inner_templates[$template_id]) {

			$ret[] = $inner;
		}
	
		return $ret;
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_INVITENEWUSERS:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_INVITENEWUSERS;
		}

		return parent::get_messagefeedback($template_id);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LATESTCOUNTS:

				// Fetch latest notifications every 30 seconds
				$this->add_jsmethod($ret, 'timeoutLoadLatestBlock');
				break;

			case GD_TEMPLATE_BLOCK_EXTERNAL:

				// This is all this block does: load the external url defined in parameter "url"
				$this->add_jsmethod($ret, 'clickURLParam');
				break;
		}
		
		return $ret;
	}

	// function get_js_setting($template_id, $atts) {

	// 	$ret = parent::get_js_setting($template_id, $atts);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_LATESTCOUNTS:

	// 			// Needed to set the notifications count on the tab
	// 			$ret['datasetcount-target'] = '#'.AAL_PoPProcessors_NotificationUtils::get_notificationcount_id();//$target;
	// 			break;
	// 	}

	// 	return $ret;
	// }

	protected function get_dataload_query_args($template_id, $atts) {

		$ret = parent::get_dataload_query_args($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LATESTCOUNTS:
			
				PoPCore_Template_Processor_SectionBlocksUtils::add_dataloadqueryargs_latestcounts($ret);
				break;
		}

		return $ret;
	}

	function get_dataload_source($template_id, $atts) {

		global $gd_template_settingsmanager;
		
		$ret = parent::get_dataload_source($template_id, $atts);

		// Add the format attr
		$latestcount = array(
			GD_TEMPLATE_BLOCK_LATESTCOUNTS,
		);
		if (in_array($template_id, $latestcount)) {
			
			$format = GD_TEMPLATEFORMAT_LATESTCOUNT;
		}
		
		if ($format) {

			$ret = add_query_arg(GD_URLPARAM_FORMAT, $format, $ret);
		}
	
		return $ret;
	}

	protected function get_block_page($template_id) {

		global $gd_template_settingsmanager;

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LATESTCOUNTS:

				// Make explicit that it is "page" hierarchy, since the Notifications can be loaded initially from any hierarchy, so if not explicit it won't find the block here
				if ($page = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_PAGE)) {

					return $page;
				}
				break;
		}

		return parent::get_block_page($template_id);
	}

	// protected function get_dataload_query_args($template_id, $atts) {

	// 	$ret = parent::get_dataload_query_args($template_id, $atts);
		
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_LATESTCOUNTS:

	// 			// Limit: bring everything
	// 			$ret['limit'] = -1;
	// 			break;
	// 	}

	// 	return $ret;
	// }

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LATESTCOUNTS:
			
				// return GD_DATALOAD_IOHANDLER_LATESTCOUNTLIST;
				return GD_DATALOAD_IOHANDLER_LIST;

			case GD_TEMPLATE_BLOCK_INVITENEWUSERS:

				return GD_DATALOAD_IOHANDLER_FORM;
		}
		
		return parent::get_iohandler($template_id);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LATESTCOUNTS:

				return GD_DATALOADER_CONVERTIBLEPOSTLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		// Set the display configuration
		$latestcount = array(
			GD_TEMPLATE_BLOCK_LATESTCOUNTS,
		);
		
		// Important: set always this value, because the IOHandler used by all different blocks is the same!
		// So if not restarting, the display will be the same as the previous one, and sometimes it doesn't need the display
		// (Eg: tables)
		if (in_array($template_id, $latestcount)) {
			
			$format = GD_TEMPLATEFORMAT_LATESTCOUNT;
		}

		if ($format) {
			$ret['iohandler-atts'][GD_URLPARAM_FORMAT] = $format;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$vars = GD_TemplateManager_Utils::get_vars();
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_INVITENEWUSERS:

				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Sending...', 'pop-coreprocessors'));
				break;

			case GD_TEMPLATE_BLOCK_LATESTCOUNTS:

				// It can be invisible, nothing to show
				$this->append_att($template_id, $atts, 'class', 'hidden');

				// Do not load initially. Load only needed when executing the setTimeout with loadLatest
				if (!$vars['fetching-json-data']) {
					$this->add_att($template_id, $atts, 'data-load', false);
				}
				break;

			case GD_TEMPLATE_BLOCK_EXTERNAL:

				// Make it invisible, nothing to show
				$this->append_att($template_id, $atts, 'class', 'hidden');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Core_Template_Processor_Blocks();