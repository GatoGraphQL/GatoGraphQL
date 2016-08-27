<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP', PoP_ServerUtils::get_template_definition('block-farms-scrollmap'));
define ('GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP', PoP_ServerUtils::get_template_definition('block-authorfarms-scrollmap'));


class OP_Template_Processor_ScrollMapSectionBlocks extends GD_EM_Template_Processor_ScrollMapBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
		);
	}

	protected function get_block_inner_template($template_id) {

		$inner_templates = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP => GD_TEMPLATE_SCROLL_FARMS_MAP,		
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP => GD_TEMPLATE_SCROLL_FARMS_MAP,
		);

		return $inner_templates[$template_id];
	}

	protected function is_postmap_block($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:
			
				return true;
		}

		return parent::is_postmap_block($template_id);
	}

	function get_title($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:

				return GD_Template_Processor_CustomSectionBlocksUtils::get_author_title();
		}
		
		return parent::get_title($template_id);
	}

	function get_submenu($template_id) {

		// Do not add for the quickview
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:

					return GD_TEMPLATE_SUBMENU_AUTHOR;
			}
		}
		
		return parent::get_submenu($template_id);
	}


	protected function show_fetchmore($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:
			
				return true;
		}

		return parent::show_fetchmore($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:
			
				return GD_TEMPLATE_FILTER_FARMS;

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:
			
				return GD_TEMPLATE_FILTER_AUTHORFARMS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_dataload_source($template_id, $atts) {

		global $gd_template_settingsmanager;
		
		switch ($template_id) {

			// These are the Profile Blocks, they will always be used inside an is_author() page
			// Then, point them not the is_page() page, but to the author url (mesym.com/p/mesym) and
			// an attr "tab" indicating this page through its path. This way, users can go straight to their 
			// information by typing their url: mesym.com/p/mesym?tab=events. Also good for future API
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:

				$ret = GD_Template_Processor_CustomSectionBlocksUtils::get_author_dataloadsource($template_id);
				break;

			default:

				$ret = parent::get_dataload_source($template_id, $atts);
				break;
		}

		// Add the format attr
		$maps = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,

			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
		);
		if (in_array($template_id, $maps)) {
			
			$format = GD_TEMPLATEFORMAT_MAP;
		}

		if ($format) {

			$ret = add_query_arg(GD_URLPARAM_FORMAT, $format, $ret);
		}
	
		return $ret;
	}

	protected function get_block_page($template_id) {

		global $gd_template_settingsmanager;

		switch ($template_id) {

			// These are the Profile Blocks, they will always be used inside an is_author() page
			// Then, point them not the is_page() page, but to the author url (mesym.com/p/mesym) and
			// an attr "tab" indicating this page through its path. This way, users can go straight to their 
			// information by typing their url: mesym.com/p/mesym?tab=events. Also good for future API
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:
			
				if ($page = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_AUTHOR)) {

					return $page;
				}
				break;
		}
	
		return parent::get_block_page($template_id);
	}

	protected function get_dataload_query_args($template_id, $atts) {

		$ret = parent::get_dataload_query_args($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:

				$ret['cat'] = POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS;
				break;
		}

		switch ($template_id) {

			// Filter by the Profile/Community
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:

				GD_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_authorcontent($ret);
				break;
		}

		// $maps = array(
		// 	GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,

		// 	GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
		// );

		// if (in_array($template_id, $maps)) {
			
		// 	// Maps: bring twice the data (eg: normally 12, bring 24)
		// 	$ret['limit'] = get_option('posts_per_page') * 2;
		// }

		// // Allow to override the limit by $atts (eg: for the Website Features, Filter section)
		// if ($limit = $this->get_att($template_id, $atts, 'limit')) {
		// 	$ret['limit'] = $limit;
		// }

		return $ret;
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:
			
				return GD_TEMPLATE_CONTROLGROUP_BLOCKPOSTLIST;
		}

		return parent::get_controlgroup_top($template_id);
	}

	function get_latestcount_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:

				return GD_TEMPLATE_LATESTCOUNT_FARMS;

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:

				return GD_TEMPLATE_LATESTCOUNT_AUTHOR_FARMS;
		}

		return parent::get_latestcount_template($template_id);
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:

				return GD_TEMPLATE_MESSAGEFEEDBACK_FARMS;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_messagefeedback_position($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:

				return 'bottom';
		}

		return parent::get_messagefeedback_position($template_id);
	}

	protected function get_iohandler($template_id) {
		
		return GD_DATALOAD_IOHANDLER_LIST;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP:
			
				return GD_DATALOADER_POSTLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		// Set the display configuration
		$maps = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
		);

		// Important: set always this value, because the IOHandler used by all different blocks is the same!
		// So if not restarting, the display will be the same as the previous one, and sometimes it doesn't need the display
		// (Eg: tables)
		// $ret[GD_URLPARAM_FORMAT] = '';
		if (in_array($template_id, $maps)) {
			
			$format = GD_TEMPLATEFORMAT_MAP;
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
new OP_Template_Processor_ScrollMapSectionBlocks();