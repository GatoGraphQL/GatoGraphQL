<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS', PoP_TemplateIDUtils::get_template_definition('block-automatedemails-scroll-details'));
define ('GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST', PoP_TemplateIDUtils::get_template_definition('block-automatedemails-scroll-list'));

class PoPTheme_Wassup_AAL_AE_Template_Processor_SectionBlocks extends PoPTheme_Wassup_AutomatedEmails_Template_Processor_SectionBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST,
		);
	}

	protected function get_block_inner_templates($template_id) {

		global $gd_template_processor_manager;

		$ret = parent::get_block_inner_templates($template_id);

		$inner_templates = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST => GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST,
		);

		if ($inner = $inner_templates[$template_id]) {

			$ret[] = $inner;
		}

		return $ret;
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:

				return GD_TEMPLATE_MESSAGEFEEDBACK_NOTIFICATIONS;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_messagefeedback_position($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
			
				return 'bottom';
		}

		return parent::get_messagefeedback_position($template_id);
	}

	function get_title($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:

				// Important: this text can only be in the title, and not in the description, because the description is saved in pop-cache/,
				// while the title is set on runtime, so only then we can have the date on the title!
				return sprintf(
					__('Your daily notifications — %s <small><a href="%s">View online</a></small>', 'poptheme-wassup-automatedemails'),
					date(get_option('date_format')),
					get_permalink(POP_AAL_PAGE_NOTIFICATIONS)
				);
		}

		return parent::get_title($template_id);
	}

	protected function get_description_abovetitle($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:

				return sprintf(
					'<p>%s</p>',
					__('These are your unread notifications from the last day.', 'poptheme-wassup-automatedemails')
				);
		}

		return parent::get_description_abovetitle($template_id, $atts);
	}

	protected function get_description_bottom($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:

				return sprintf(
					// '<p>&nbsp;</p><p>%s</p>%s',
					'<p>&nbsp;</p><p>%s</p>',
					sprintf(
						'<a href="%s">%s</a>',
						get_permalink(POP_AAL_PAGE_NOTIFICATIONS),
						sprintf(
							__('View all your notifications on %s', 'poptheme-wassup-automatedemails'),
							get_bloginfo('name')
						)
					)//,
					// PoP_EmailSender_CustomUtils::get_preferences_footer()
				);
		}

		return parent::get_description_bottom($template_id, $atts);
	}

	function get_dataload_source($template_id, $atts) {

		global $gd_template_settingsmanager;
		
		$ret = parent::get_dataload_source($template_id, $atts);

		// Add the format attr
		$details = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST,
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

	protected function get_dataload_query_args($template_id, $atts) {

		$ret = parent::get_dataload_query_args($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:

				// Return the notifications from within the last 24 hs (timestamp is runtime, it must not be cached)
				$ret['hist_time_compare'] = '>=';

				// Bring only the non-read notifications
				$ret['joinstatus'] = true;
				$ret['status'] = 'null';

				// Limit: 2 times the default for posts
				$ret['limit'] = get_option('posts_per_page') * 2;
				break;
		}

		return $ret;
	}

	protected function get_runtime_dataload_query_args($template_id, $atts) {

		$ret = parent::get_runtime_dataload_query_args($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:

				// Return the notifications from within the last 24 hs
				$yesterday = strtotime("-1 day", POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/);
				$ret['hist_time'] = $yesterday;
				break;
		}

		return $ret;
	}

	protected function get_iohandler($template_id) {

		return GD_DATALOAD_IOHANDLER_NOTIFICATIONLIST;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST:
			
				return GD_DATALOADER_NOTIFICATIONLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);

		// Set the display configuration
		$details = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST,
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
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AAL_AE_Template_Processor_SectionBlocks();