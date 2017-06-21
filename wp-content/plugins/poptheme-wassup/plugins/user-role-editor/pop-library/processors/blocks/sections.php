<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/*********************************************
 * My Content Tables
 *********************************************/
define ('GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT', PoP_ServerUtils::get_template_definition('block-mymembers-table-edit'));

/*--------------------------------------------
 * My Content Full Post Preview
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-mymembers-scroll-fullviewpreview'));

/*********************************************
 * Typeaheads
 *********************************************/
define ('GD_TEMPLATE_BLOCK_COMMUNITIES_TYPEAHEAD', PoP_ServerUtils::get_template_definition('block-communities-typeahead'));
define ('GD_TEMPLATE_BLOCK_ORGANIZATIONS_TYPEAHEAD', PoP_ServerUtils::get_template_definition('block-organizations-typeahead'));
define ('GD_TEMPLATE_BLOCK_INDIVIDUALS_TYPEAHEAD', PoP_ServerUtils::get_template_definition('block-individuals-typeahead'));
define ('GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD', PoP_ServerUtils::get_template_definition('block-authorusers-typeahead'));

/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
 * Common blocks (Home/Page/Author/Single)
 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
/*--------------------------------------------
 * Navigator
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR', PoP_ServerUtils::get_template_definition('block-organizations-scroll-navigator'));
define ('GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR', PoP_ServerUtils::get_template_definition('block-individuals-scroll-navigator'));

/*--------------------------------------------
 * Addons
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS', PoP_ServerUtils::get_template_definition('block-organizations-scroll-addons'));
define ('GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS', PoP_ServerUtils::get_template_definition('block-individuals-scroll-addons'));

/*--------------------------------------------
 * Details: Thumb, title and excerpt
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS', PoP_ServerUtils::get_template_definition('block-communities-scroll-details'));
define ('GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS', PoP_ServerUtils::get_template_definition('block-organizations-scroll-details'));
define ('GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS', PoP_ServerUtils::get_template_definition('block-individuals-scroll-details'));

define ('GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS', PoP_ServerUtils::get_template_definition('block-authormembers-scroll-details'));

/*--------------------------------------------
 * Full Post
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-communities-scroll-fullview'));
define ('GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-organizations-scroll-fullview'));
define ('GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-individuals-scroll-fullview'));

define ('GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-authormembers-scroll-fullview'));

/*--------------------------------------------
 * Thumbnail
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL', PoP_ServerUtils::get_template_definition('block-communities-scroll-thumbnail'));
define ('GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL', PoP_ServerUtils::get_template_definition('block-organizations-scroll-thumbnail'));
define ('GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL', PoP_ServerUtils::get_template_definition('block-individuals-scroll-thumbnail'));

define ('GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL', PoP_ServerUtils::get_template_definition('block-authormembers-scroll-thumbnail'));

/*--------------------------------------------
 * List
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-communities-scroll-list'));
define ('GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-organizations-scroll-list'));
define ('GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-individuals-scroll-list'));

// define ('GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-organizationmembers-scroll-list'));

define ('GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-authormembers-scroll-list'));

/*********************************************
 * Post Carousels
 *********************************************/
define ('GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL', PoP_ServerUtils::get_template_definition('block-authormembers-carousel'));

class GD_URE_Template_Processor_CustomSectionBlocks extends GD_Template_Processor_SectionBlocksBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_COMMUNITIES_TYPEAHEAD,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_TYPEAHEAD,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_TYPEAHEAD,
			GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS,
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,
			// GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST,
			
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST,
			
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL,
		);
	}

	protected function get_block_inner_templates($template_id) {

		global $gd_template_processor_manager;

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS_BIG;
				$ret[] = GD_TEMPLATE_TABLE_MYMEMBERS;
				break;

			// Logic below already done in the parent
			// default:

			// 	if ($block_inner_template = $this->get_block_inner_template($template_id)) {

			// 		$ret[] = $block_inner_template;
			// 	}
			// 	break;
		}

		return $ret;
	}

	protected function get_block_inner_template($template_id) {

		$inner_templates = array(

			/*********************************************
			 * Typeaheads
			 *********************************************/
			// Straight to the layout
			GD_TEMPLATE_BLOCK_COMMUNITIES_TYPEAHEAD => GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_TYPEAHEAD => GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_TYPEAHEAD => GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT,

			GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD => GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT,

			/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			 * Common blocks (Home/Page/Author/Single)
			 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR => GD_TEMPLATE_SCROLL_ORGANIZATIONS_NAVIGATOR,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR => GD_TEMPLATE_SCROLL_INDIVIDUALS_NAVIGATOR,
			
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS => GD_TEMPLATE_SCROLL_ORGANIZATIONS_ADDONS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS => GD_TEMPLATE_SCROLL_INDIVIDUALS_ADDONS,
			
			/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			 * Home/Page blocks
			 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_COMMUNITIES_DETAILS,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_ORGANIZATIONS_DETAILS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_INDIVIDUALS_DETAILS,
			
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_COMMUNITIES_FULLVIEW,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_ORGANIZATIONS_FULLVIEW,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_INDIVIDUALS_FULLVIEW,
			
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL => GD_TEMPLATE_SCROLL_COMMUNITIES_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL => GD_TEMPLATE_SCROLL_ORGANIZATIONS_THUMBNAIL,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL => GD_TEMPLATE_SCROLL_INDIVIDUALS_THUMBNAIL,
			
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST => GD_TEMPLATE_SCROLL_COMMUNITIES_LIST,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST => GD_TEMPLATE_SCROLL_ORGANIZATIONS_LIST,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST => GD_TEMPLATE_SCROLL_INDIVIDUALS_LIST,
			
			// GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST => GD_TEMPLATE_SCROLL_ORGANIZATIONMEMBERS_LIST,

			/*********************************************
			 * My Content Full Post Previews
			 *********************************************/
			GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW,

			/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			 * Author blocks
			 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_AUTHORMEMBERS_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_AUTHORMEMBERS_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL => GD_TEMPLATE_SCROLL_AUTHORMEMBERS_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST => GD_TEMPLATE_SCROLL_AUTHORMEMBERS_LIST,
			
			/*********************************************
			 * Post Carousels
			 *********************************************/
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL => GD_TEMPLATE_CAROUSEL_AUTHORMEMBERS,
		);

		return $inner_templates[$template_id];
	}

	protected function show_fetchmore($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:

			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:

			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR:
			
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS:
			
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST:

			// case GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:

				return true;
		}

		return parent::show_fetchmore($template_id);
	}

	protected function filter_hidden($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR:

			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS:
			
				return true;
		}

		return parent::filter_hidden($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_INDIVIDUALS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST:
			
				return GD_TEMPLATE_FILTER_INDIVIDUALS;

			case GD_TEMPLATE_BLOCK_COMMUNITIES_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST:
			// case GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST:
			
				return GD_TEMPLATE_FILTER_ORGANIZATIONS;

			case GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD:
			
				return GD_TEMPLATE_FILTER_WILDCARDUSERS;

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:
			
				return GD_TEMPLATE_FILTER_AUTHORWILDCARDUSERS;

			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:

				return GD_TEMPLATE_FILTER_MYMEMBERS;
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
			case GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

				$ret = GD_Template_Processor_CustomSectionBlocksUtils::get_author_dataloadsource($template_id);
				break;

			default:

				$ret = parent::get_dataload_source($template_id, $atts);
				break;
		}

		// Add the format attr
		$tables = array(
			GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT,
		);
		$details = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
			
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,

			// GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST,
		);
		$typeaheads = array(
			GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD,
		);
		$carousels = array(
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL,
		);
		if (in_array($template_id, $tables)) {
			
			$format = GD_TEMPLATEFORMAT_TABLE;
		}
		elseif (in_array($template_id, $details)) {
			
			$format = GD_TEMPLATEFORMAT_DETAILS;
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
		elseif (in_array($template_id, $typeaheads)) {
			
			$format = GD_TEMPLATEFORMAT_TYPEAHEAD;
		}
		elseif (in_array($template_id, $carousels)) {
			
			$format = GD_TEMPLATEFORMAT_CAROUSEL;
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
			case GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:
			
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

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

				// $ret['role'] = GD_ROLE_PROFILE;
				$ret['orderby'] = 'registered';
				$ret['order'] = 'DESC';
				break;

			case GD_TEMPLATE_BLOCK_COMMUNITIES_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST:
			
				$ret['role'] = GD_URE_ROLE_COMMUNITY;
				break;

			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST:
			// case GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST:
			
				$ret['role'] = GD_URE_ROLE_ORGANIZATION;
				break;

			case GD_TEMPLATE_BLOCK_INDIVIDUALS_TYPEAHEAD:

			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST:
			
				$ret['role'] = GD_URE_ROLE_INDIVIDUAL;
				break;
		}

		$fullviews = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW,

			GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
		);

		// Bring only 3 items for the fullview
		if (in_array($template_id, $fullviews)) {
			
			$ret['limit'] = 6;
		}

		// Allow to override the limit by $atts (eg: for the Website Features, Filter section)
		if ($limit = $this->get_att($template_id, $atts, 'limit')) {
			$ret['limit'] = $limit;
		}

		// Allow to override the include by $atts (eg: for GetPoP Organization Membes demonstration)
		if ($include = $this->get_att($template_id, $atts, 'include')) {
			$ret['include'] = $include;
		}

		return $ret;
	}

	protected function get_runtime_dataload_query_args($template_id, $atts) {

		$ret = parent::get_runtime_dataload_query_args($template_id, $atts);
		
		$vars = GD_TemplateManager_Utils::get_vars();
		switch ($template_id) {

			// Members of the Community
			case GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:
			
				$author = $vars['global-state']['author']/*global $author*/;
				// If the profile is not a community, then return no users at all (Eg: an Organization opting out from having members)
				if (gd_ure_is_community($author)) {
					
					URE_CommunityUtils::add_dataloadqueryargs_communitymembers($ret, $author);
				}
				break;

			// Members of the Community
			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
			
				$current_user = $vars['global-state']['current-user-id']/*get_current_user_id()*/;
				if (gd_ure_is_community($current_user)) {
					
					if (!isset($ret['meta-query'])) { $ret['meta-query'] = array(); }
					
					$ret['meta-query'][] = array(
						'key' => GD_MetaManager::get_meta_key(GD_URE_METAKEY_PROFILE_COMMUNITIES, GD_META_TYPE_USER),
						'value' => $current_user,
						'compare' => 'IN'
					);
				}
				break;
		}

		return $ret;
	}

	protected function get_description($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
				
				$invitenew_processor = $gd_template_processor_manager->get_processor(GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS);
				$invitenew = sprintf(
					'<a class="btn btn-xs btn-success" href="%s" target="%s"><i class="fa fa-fw %s"></i>%s</a>',
					$invitenew_processor->get_href(GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS, $atts),
					$invitenew_processor->get_target(GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS, $atts),
					$invitenew_processor->get_fontawesome(GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS, $atts),
					$invitenew_processor->get_label(GD_TEMPLATE_ANCHORCONTROL_INVITENEWMEMBERS, $atts)
				);
				$placeholder = '<strong>%s</strong>: <br/>%s';
				$placeholder_item = '<h3>%s</h3>%s';
				$placeholder_li = '<li>%s</li>';
				
				$help_header = sprintf(
					'<i class="fa fa-fw fa-info"></i>%s',
					__('Show Help', 'poptheme-wassup')
				);
				$help_body = 
					sprintf(
						$placeholder_item,
						__('Inviting users to become your members', 'poptheme-wassup'),
						sprintf(
							__('Please click on %s to invite your physical Organization\'s members to become your members in the website.', 'poptheme-wassup'),
							$invitenew
						)
					).
					sprintf(
						$placeholder_item,
						__('What is a member?', 'poptheme-wassup'),
						__('This depends on your Organization. Some examples:<br/>', 'poptheme-wassup').
						'<ul>'.
							sprintf($placeholder_li, __('University teachers/students', 'poptheme-wassup')).
							sprintf($placeholder_li, __('NGO volunteers', 'poptheme-wassup')).
							sprintf($placeholder_li, __('Company staff', 'poptheme-wassup')).
							sprintf($placeholder_li, __('etc', 'poptheme-wassup')).
						'</ul>'
					).
					sprintf(
						$placeholder_item,
						__('What happens when my Organization has members?', 'poptheme-wassup'),
						__('If they have privilege <em>Contribute content</em> on, whenever they post any content in the website (eg: a new event, article, project, etc) will also show up under your Organization\'s profile. If this privilege is off, they will just appear under "My members"', 'poptheme-wassup')
					).
					sprintf(
						$placeholder_item,
						__('Editing current members\' status', 'poptheme-wassup'),
						__('The users listed below have declared themselves to be members of your Organization. You can click on "Edit Membership" to edit the settings for each one of them:', 'poptheme-wassup').
						sprintf(
							'<ul>%s%s%s</ul>',
							sprintf(
								$placeholder_li,
								sprintf(
									$placeholder,
									__('Status', 'poptheme-wassup'),
									__('<em>Active</em> if the user is truly your member, or <em>Rejected</em> otherwise.<br/><em>Rejected</em> users will not appear as your Organization\'s members, or contribute content.', 'poptheme-wassup')
								)
							),
							sprintf(
								$placeholder_li,
								sprintf(
									$placeholder,
									__('Privileges', 'poptheme-wassup'),
									__('<em>Contribute content</em> will add the member\'s content to your profile.', 'poptheme-wassup')
								)
							),
							sprintf(
								$placeholder_li,
								sprintf(
									$placeholder,
									__('Tags', 'poptheme-wassup'),
									__('What is the type of relationship from this member to your Organization.', 'poptheme-wassup')
								)
							)
						)
					);
				// $faq_header = sprintf(
				// 	'<i class="fa fa-fw fa-question"></i>%s',
				// 	__('Show FAQs', 'poptheme-wassup')
				// );
				// $faq_body = 
				// 	sprintf(
				// 		$placeholder_item,
				// 		__('What is a member?', 'poptheme-wassup'),
				// 		__('This depends on your Organization. Some examples:<br/>', 'poptheme-wassup').
				// 		'<ul>'.
				// 			sprintf($placeholder_li, __('University teachers/students', 'poptheme-wassup')).
				// 			sprintf($placeholder_li, __('NGO volunteers', 'poptheme-wassup')).
				// 			sprintf($placeholder_li, __('Company staff', 'poptheme-wassup')).
				// 			sprintf($placeholder_li, __('etc', 'poptheme-wassup')).
				// 		'</ul>'
				// 	).
				// 	sprintf(
				// 		$placeholder_item,
				// 		__('What happens when my Organization has members?', 'poptheme-wassup'),
				// 		__('If they have privilege <em>Contribute content</em> on, whenever they post any content in the website (eg: a new event, article, project, etc) will also show up under your Organization\'s profile. If this privilege is off, they will just appear under "My members"', 'poptheme-wassup')
				// 	).
				// 	sprintf(
				// 		$placeholder_item,
				// 		__('What is the benefit of having members for my Organization?', 'poptheme-wassup'),
				// 		__('Your Organization will get the credit for the content posted by your members without having to do much: they are the ones creating and posting their own content.', 'poptheme-wassup')
				// 	).
				// 	sprintf(
				// 		$placeholder_item,
				// 		__('Can you give me an example of this?', 'poptheme-wassup'),
				// 		__('Sure! Imagine your Organization is a University and has many students registered as members, and each one of them posts Discussions explaining their knowledge on some environmental topic they learn in class, and Projects initiated in their campuses.<br/><br/>', 'poptheme-wassup').
				// 		__('All this environmental content will also show up in the University\'s profile, with the following positive consequences:<br/>', 'poptheme-wassup').
				// 		'<ul>'.
				// 			sprintf($placeholder_li, __('The University can show off its environmental record, and gain more environmentally-aware new students.', 'poptheme-wassup')).
				// 			sprintf($placeholder_li, __('The students can share their knowledge with their classmates, year-on-year fellow students, and even other University students.', 'poptheme-wassup')).
				// 			sprintf($placeholder_li, __('Other Universities will follow, eventually creating a network of environmental knowledge and action.', 'poptheme-wassup')).
				// 		'</ul>'
				// 	).
				// 	sprintf(
				// 		$placeholder_item,
				// 		__('What is the benefit for my members?', 'poptheme-wassup'),
				// 		__('In addition to being part of the network mentioned above, they will benefit by showing off themselves: their profile effectively constitutes an "Environmental CV".', 'poptheme-wassup')
				// 	);
				
				$placeholder_list = '<div class="panel panel-info"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" href="#block-mymembers-description-%1$s" aria-expanded="false" aria-controls="block-mymembers-description-%1$s">%2$s</a></h4></div><div id="block-mymembers-description-%1$s" class="panel-body collapse">%3$s</div></div>';
				$description = sprintf(
					// '<div class="panel-group">%s%s</div>',
					'<div class="panel-group">%s</div>',
					/*sprintf(
						$placeholder_list,
						'faq',
						$faq_header,
						$faq_body
					),*/
					sprintf(
						$placeholder_list,
						'help',
						$help_header,
						$help_body
					)
				);
				return $description;
		}

		return parent::get_description($template_id, $atts);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST:

			// case GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:

				return GD_TEMPLATE_CONTROLGROUP_BLOCKUSERLIST;

			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
				
				return GD_TEMPLATE_CONTROLGROUP_MYBLOCKMEMBERS;
		}

		return parent::get_controlgroup_top($template_id);
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

				return GD_TEMPLATE_MESSAGEFEEDBACK_MEMBERS;

			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_MYMEMBERS;

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST:
			
			// case GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST:

				return GD_TEMPLATE_MESSAGEFEEDBACK_ORGANIZATIONS;

			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_INDIVIDUALS;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_messagefeedback_position($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:

			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
			
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR:
			
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS:
			
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST:

			// case GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

				return 'bottom';
		}

		return parent::get_messagefeedback_position($template_id);
	}

	function get_title($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

				return '';

			// case GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:
			// case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

				return GD_Template_Processor_CustomSectionBlocksUtils::get_author_title();
		}
		
		return parent::get_title($template_id);
	}

	function get_submenu($template_id) {

		// Do not add for the quickview
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				// case GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD:
				case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
				case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
				case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
				case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:
				// case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

					return GD_TEMPLATE_SUBMENU_AUTHOR;
			}
		}
		
		return parent::get_submenu($template_id);
	}

	protected function get_iohandler($template_id) {
	
		return GD_DATALOAD_IOHANDLER_LIST;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMUNITIES_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_TYPEAHEAD:

			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR:
			
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS:
			
			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL:

			case GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST:

			// case GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:

				// return GD_DATALOADER_PROFILELIST;
				return GD_DATALOADER_USERLIST;

			case GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD:

				// return GD_URE_DATALOADER_COMMUNITY_PROFILELIST;
				return GD_URE_DATALOADER_COMMUNITY_USERLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		// Set the display configuration
		$navigators = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR,
		);
		$addons = array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS,
		);
		$details = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW,

			GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,

			// GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST,

			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST,
		);
		$tables = array(
			GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT,
		);
		$typeaheads = array(
			GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD,
		);
		$carousels = array(
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL,
		);
		// Important: set always this value, because the IOHandler used by all different blocks is the same!
		// So if not restarting, the display will be the same as the previous one, and sometimes it doesn't need the display
		// (Eg: tables)
		// $ret[GD_URLPARAM_FORMAT] = '';
		if (in_array($template_id, $navigators)) {
			
			$format = GD_TEMPLATEFORMAT_NAVIGATOR;
		}
		elseif (in_array($template_id, $addons)) {
			
			$format = GD_TEMPLATEFORMAT_ADDONS;
		}
		elseif (in_array($template_id, $details)) {
			
			$format = GD_TEMPLATEFORMAT_DETAILS;
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
		elseif (in_array($template_id, $tables)) {
			
			$format = GD_TEMPLATEFORMAT_TABLE;
		}
		elseif (in_array($template_id, $typeaheads)) {
			
			$format = GD_TEMPLATEFORMAT_TYPEAHEAD;
		}
		elseif (in_array($template_id, $carousels)) {
			
			$format = GD_TEMPLATEFORMAT_CAROUSEL;
		}

		if ($format) {
			$ret['iohandler-atts'][GD_URLPARAM_FORMAT] = $format;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;
			
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL:

				$this->append_att($template_id, $atts, 'class', 'pop-block-carousel block-users-carousel');
				break;

			// Members of the Community
			case GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST:
			
				$vars = GD_TemplateManager_Utils::get_vars();
				$author = $vars['global-state']['author']/*global $author*/;
				// If the profile is not a community, then return no users at all (Eg: an Organization opting out from having members)
				if (!gd_ure_is_community($author)) {
					
					$this->add_att($template_id, $atts, 'data-load', false);						
				}
				break;
				
			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
				
				$this->merge_att($template_id, $atts, 'params', array(
					'data-neededrole' => GD_URE_ROLE_COMMUNITY
				));
				break;
		}

		// Needed for restraining to 600px together with class pop-outerblock
		$tables = array(
			GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT,
		);
		$feeds = array(
			
			GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS,
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,

			// GD_TEMPLATE_BLOCK_ORGANIZATIONMEMBERS_SCROLL_LIST,
			
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST,
		);
		$carousels = array(
			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL,
		);
		if (in_array($template_id, $tables)) {
			$class = 'tableblock';
		}
		elseif (in_array($template_id, $feeds)) {
			$class = 'feed';
		}
		elseif (in_array($template_id, $carousels)) {
			$class = 'carousel';
		}
		if ($class) {
			$this->append_att($template_id, $atts, 'class', $class);
		}
		
		
		return parent::init_atts($template_id, $atts);
	}

	function block_requires_user_state($template_id, $atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:

				return true;
		}

		return parent::block_requires_user_state($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:

				// Only reload/destroy if these are main blocks
				if ($this->get_att($template_id, $atts, 'is-mainblock')) {

					// destroyPageOnUserNoRole needed for the following sequence:
					// 1. Community opens My Members page
					// 2. Community edits profile, says "I do not accept members"
					// 3. Then it's just an Organization, close page My Members
					$this->add_jsmethod($ret, 'destroyPageOnUserNoRole');

					// Code below already executed in the parent
					// $this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
					// $this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');
				}
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomSectionBlocks();