<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP', PoP_ServerUtils::get_template_definition('block-communities-scrollmap'));
define ('GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP', PoP_ServerUtils::get_template_definition('block-organizations-scrollmap'));
define ('GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP', PoP_ServerUtils::get_template_definition('block-individuals-scrollmap'));

define ('GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP', PoP_ServerUtils::get_template_definition('block-authormembers-scrollmap'));

class GD_URE_Template_Processor_CustomScrollMapSectionBlocks extends GD_EM_Template_Processor_ScrollMapBlocksBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP,
		);
	}

	protected function is_usermap_block($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

				return true;
		}

		return parent::is_usermap_block($template_id);
	}

	protected function get_block_inner_template($template_id) {

		$inner_templates = array(

			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP => GD_TEMPLATE_SCROLL_COMMUNITIES_MAP,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP => GD_TEMPLATE_SCROLL_ORGANIZATIONS_MAP,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP => GD_TEMPLATE_SCROLL_INDIVIDUALS_MAP,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP => GD_TEMPLATE_SCROLL_AUTHORMEMBERS_MAP,		
		);

		return $inner_templates[$template_id];
	}

	function get_title($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

				return GD_Template_Processor_CustomSectionBlocksUtils::get_author_title();
		}
		
		return parent::get_title($template_id);
	}

	function get_submenu($template_id) {

		// Do not add for the quickview
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

					return GD_TEMPLATE_SUBMENU_AUTHOR;
			}
		}
		
		return parent::get_submenu($template_id);
	}

	protected function show_fetchmore($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

				return true;
		}

		return parent::show_fetchmore($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP:
			
				return GD_TEMPLATE_FILTER_INDIVIDUALS;

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP:
			
				return GD_TEMPLATE_FILTER_ORGANIZATIONS;

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:
			
				return GD_TEMPLATE_FILTER_AUTHORWILDCARDUSERS;
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
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

				$ret = GD_Template_Processor_CustomSectionBlocksUtils::get_author_dataloadsource($template_id);
				break;

			default:

				$ret = parent::get_dataload_source($template_id, $atts);
				break;
		}

		$maps = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP,
		);

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
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:
			
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

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP:
			
				$ret['role'] = GD_URE_ROLE_COMMUNITY;
				break;

			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP:
			
				$ret['role'] = GD_URE_ROLE_ORGANIZATION;
				break;

			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP:
			
				$ret['role'] = GD_URE_ROLE_INDIVIDUAL;
				break;
		}

		return $ret;
	}

	protected function get_runtime_dataload_query_args($template_id, $atts) {

		$ret = parent::get_runtime_dataload_query_args($template_id, $atts);
		
		switch ($template_id) {

			// Members of the Community
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:
			
				$vars = GD_TemplateManager_Utils::get_vars();
				$author = $vars['global-state']['author']/*global $author*/;
				// If the profile is not a community, then return no users at all (Eg: an Organization opting out from having members)
				if (gd_ure_is_community($author)) {
					
					URE_CommunityUtils::add_dataloadqueryargs_communitymembers($ret, $author);
				}
				break;
		}

		return $ret;
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

				return GD_TEMPLATE_CONTROLGROUP_BLOCKUSERLIST;
		}

		return parent::get_controlgroup_top($template_id);
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

				return GD_TEMPLATE_MESSAGEFEEDBACK_MEMBERS;

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_ORGANIZATIONS;

			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_INDIVIDUALS;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_messagefeedback_position($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

				return 'bottom';
		}

		return parent::get_messagefeedback_position($template_id);
	}

	protected function get_iohandler($template_id) {
		
		return GD_DATALOAD_IOHANDLER_LIST;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:

				return GD_DATALOADER_USERLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		$maps = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP,
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

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			// Members of the Community
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP:
			
				$vars = GD_TemplateManager_Utils::get_vars();
				$author = $vars['global-state']['author']/*global $author*/;
				// If the profile is not a community, then return no users at all (Eg: an Organization opting out from having members)
				if (!gd_ure_is_community($author)) {
					
					$this->add_att($template_id, $atts, 'data-load', false);						
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomScrollMapSectionBlocks();