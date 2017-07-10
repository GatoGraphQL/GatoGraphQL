<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/*--------------------------------------------
 * Details: Thumb, title and excerpt
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS', PoP_ServerUtils::get_template_definition('block-automatedemails-latestposts-scroll-details'));

/*--------------------------------------------
 * Full Post
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('block-automatedemails-latestposts-scroll-simpleview'));
define ('GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-automatedemails-latestposts-scroll-fullview'));

/*--------------------------------------------
 * Thumbnail
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL', PoP_ServerUtils::get_template_definition('block-automatedemails-latestposts-scroll-thumbnail'));

/*--------------------------------------------
 * List
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-automatedemails-latestposts-scroll-list'));

class PoPTheme_Wassup_AE_Template_Processor_SectionBlocks extends PoPTheme_Wassup_AutomatedEmails_Template_Processor_SectionBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST,
		);
	}

	protected function get_block_inner_template($template_id) {

		$inner_templates = array(

			/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			 * Home/Page blocks
			 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
			
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_DETAILS,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW => GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL => GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST => GD_TEMPLATE_SCROLL_AUTOMATEDEMAILS_LATESTPOSTS_LIST,
		);

		return $inner_templates[$template_id];
	}

	// function get_title($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:

	// 			return '';
	// 	}

	// 	return parent::get_title($template_id);
	// }

	protected function get_description($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:

				return sprintf(
					'<p>%s</p><h1>%s</h1>',
					sprintf(
						__('This is the latest content uploaded to %s during last week. Do you want to add your content? <a href="%s">Click here</a> to share it with our community.', 'poptheme-wassup-automatedemails'),
						get_bloginfo('name'),
						get_permalink(POPTHEME_WASSUP_PAGE_ADDCONTENT)
					),
					sprintf(
						__('Latest content — %s <small><a href="%s">View online</a></small>', 'poptheme-wassup-automatedemails'),
						date(get_option('date_format')),
						get_permalink(POP_WPAPI_PAGE_ALLCONTENT)
					)
				);
		}

		return parent::get_description($template_id, $atts);
	}

	protected function get_description_bottom($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:

				return sprintf(
					'<p>&nbsp;</p><p>%s</p>%s',
					sprintf(
						'<a href="%s">%s</a>',
						get_permalink(POP_WPAPI_PAGE_ALLCONTENT),
						sprintf(
							__('View all content on %s', 'poptheme-wassup-automatedemails'),
							get_bloginfo('name')
						)
					),
					PoP_EmailSender_CustomUtils::get_preferences_footer()
				);
		}

		return parent::get_description_bottom($template_id, $atts);
	}

	// protected function show_status($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:

	// 			return false;
	// 	}

	// 	return parent::show_status($template_id);
	// }

	// protected function show_disabled_layer($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:

	// 			return false;
	// 	}

	// 	return parent::show_disabled_layer($template_id);
	// }

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:

				return GD_TEMPLATE_MESSAGEFEEDBACK_ALLCONTENT;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_messagefeedback_position($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:
			
				return 'bottom';
		}

		return parent::get_messagefeedback_position($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:

				return GD_TEMPLATE_FILTER_WILDCARDPOSTS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_dataload_source($template_id, $atts) {

		$ret = parent::get_dataload_source($template_id, $atts);

		// Add the format attr
		$details = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST,
		);
		if (in_array($template_id, $details)) {
			
			$format = GD_TEMPLATEFORMAT_DETAILS;
		}
		elseif (in_array($template_id, $simpleviews)) {
			
			$format = GD_TEMPLATEFORMAT_SIMPLEVIEW;
		}
		elseif (in_array($template_id, $fullviews)) {
			
			$format = GD_TEMPLATEFORMAT_FULLVIEW;
		}
		elseif (in_array($template_id, $thumbnails)) {
			
			$format = GD_TEMPLATEFORMAT_THUMBNAIL;
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

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:

				PoPCore_Template_Processor_SectionBlocksUtils::add_dataloadqueryargs_allcontent($ret);

				// Return the posts created after the given timestamp
				$start_date = strtotime("-7 day", POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/);
				$ret['date_query'] = array(
					array(
						'after' => date('Y-m-d H:i:s', $start_date),
						'inclusive' => true,
					)
				);
				break;
		}
		
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW,
		);
		
		if (in_array($template_id, $simpleviews) || in_array($template_id, $fullviews)) {
			
			$ret['limit'] = 6;
		}

		// Allow to override the limit by $atts (eg: for the Website Features, Filter section)
		if ($limit = $this->get_att($template_id, $atts, 'limit')) {
			$ret['limit'] = $limit;
		}

		return $ret;
	}

	protected function get_iohandler($template_id) {

		return GD_DATALOAD_IOHANDLER_LIST;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST:
			
				return GD_DATALOADER_CONVERTIBLEPOSTLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);

		// Set the display configuration
		$details = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST,
		);

		if (in_array($template_id, $details)) {
			
			$format = GD_TEMPLATEFORMAT_DETAILS;
		}
		elseif (in_array($template_id, $simpleviews)) {
			
			$format = GD_TEMPLATEFORMAT_SIMPLEVIEW;
		}
		elseif (in_array($template_id, $fullviews)) {
			
			$format = GD_TEMPLATEFORMAT_FULLVIEW;
		}
		elseif (in_array($template_id, $thumbnails)) {
			
			$format = GD_TEMPLATEFORMAT_THUMBNAIL;
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
new PoPTheme_Wassup_AE_Template_Processor_SectionBlocks();