<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/*********************************************
 * My Content Tables
 *********************************************/
define ('GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT', PoP_ServerUtils::get_template_definition('block-myfarms-table-edit'));

/*--------------------------------------------
 * My Content Simple Post Preview
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW', PoP_ServerUtils::get_template_definition('block-myfarms-scroll-simpleviewpreview'));

/*--------------------------------------------
 * My Content Full Post Preview
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW', PoP_ServerUtils::get_template_definition('block-myfarms-scroll-fullviewpreview'));

/*********************************************
 * Typeaheads
 *********************************************/
define ('GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD', PoP_ServerUtils::get_template_definition('block-farms-typeahead'));

/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
 * Common blocks (Home/Page/Author/Single)
 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
/*--------------------------------------------
 * Navigator
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR', PoP_ServerUtils::get_template_definition('block-farms-scroll-navigator'));

/*--------------------------------------------
 * Addons
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS', PoP_ServerUtils::get_template_definition('block-farms-scroll-addons'));

/*--------------------------------------------
 * Details: Thumb, title and excerpt
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS', PoP_ServerUtils::get_template_definition('block-farms-scroll-details'));
define ('GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS', PoP_ServerUtils::get_template_definition('block-authorfarms-scroll-details'));
define ('GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS', PoP_ServerUtils::get_template_definition('block-tagfarms-scroll-details'));

/*--------------------------------------------
 * Simple Post
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('block-farms-scroll-simpleview'));
define ('GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('block-authorfarms-scroll-simpleview'));
define ('GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('block-tagfarms-scroll-simpleview'));

/*--------------------------------------------
 * Full Post
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-farms-scroll-fullview'));
define ('GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-authorfarms-scroll-fullview'));
define ('GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW', PoP_ServerUtils::get_template_definition('block-tagfarms-scroll-fullview'));

/*--------------------------------------------
 * Thumbnail
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL', PoP_ServerUtils::get_template_definition('block-farms-scroll-thumbnail'));
define ('GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL', PoP_ServerUtils::get_template_definition('block-authorfarms-scroll-thumbnail'));
define ('GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL', PoP_ServerUtils::get_template_definition('block-tagfarms-scroll-thumbnail'));

/*--------------------------------------------
 * List
 --------------------------------------------*/
define ('GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-farms-scroll-list'));
define ('GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-authorfarms-scroll-list'));
define ('GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST', PoP_ServerUtils::get_template_definition('block-tagfarms-scroll-list'));

class OP_Template_Processor_SectionBlocks extends GD_Template_Processor_SectionBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,

			GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,

			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,

			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST,
		);
	}

	protected function get_block_inner_template($template_id) {

		$inner_templates = array(

			/*********************************************
			 * Typeaheads
			 *********************************************/
			// Straight to the layout
			GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD => GD_TEMPLATE_LAYOUTPOST_TYPEAHEAD_COMPONENT,
			
			/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			 * Common blocks (Home/Page/Author/Single)
			 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

			GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR => GD_TEMPLATE_SCROLL_FARMS_NAVIGATOR,

			GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS => GD_TEMPLATE_SCROLL_FARMS_ADDONS,
			
			/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			 * Home/Page blocks
			 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
			
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_FARMS_DETAILS,
			
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW => GD_TEMPLATE_SCROLL_FARMS_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_FARMS_FULLVIEW,
			
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL => GD_TEMPLATE_SCROLL_FARMS_THUMBNAIL,
						
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST => GD_TEMPLATE_SCROLL_FARMS_LIST,
			
			/*********************************************
			 * My Content Tables
			 *********************************************/
			GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT => GD_TEMPLATE_TABLE_MYFARMS,

			/*********************************************
			 * My Content Full Post Previews
			 *********************************************/
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW => GD_TEMPLATE_SCROLL_MYFARMS_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW => GD_TEMPLATE_SCROLL_MYFARMS_FULLVIEWPREVIEW,

			/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			 * Author blocks
			 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_FARMS_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW => GD_TEMPLATE_SCROLL_FARMS_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_AUTHORFARMS_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL => GD_TEMPLATE_SCROLL_FARMS_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST => GD_TEMPLATE_SCROLL_FARMS_LIST,

			/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
			 * Tag blocks
			 *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS => GD_TEMPLATE_SCROLL_FARMS_DETAILS,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW => GD_TEMPLATE_SCROLL_FARMS_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW => GD_TEMPLATE_SCROLL_FARMS_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL => GD_TEMPLATE_SCROLL_FARMS_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST => GD_TEMPLATE_SCROLL_FARMS_LIST,
		);

		return $inner_templates[$template_id];
	}

	function get_title($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:

				return GD_Template_Processor_CustomSectionBlocksUtils::get_author_title();

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

				return GD_Template_Processor_CustomSectionBlocksUtils::get_author_title();
		}
		
		return parent::get_title($template_id);
	}

	function get_submenu($template_id) {

		// Do not add for the quickview
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
				case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
				case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
				case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
				case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:

					return GD_TEMPLATE_SUBMENU_AUTHOR;

				case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
				case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
				case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
				case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
				case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

					return GD_TEMPLATE_SUBMENU_TAG;
			}
		}
		
		return parent::get_submenu($template_id);
	}

	protected function show_fetchmore($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:
			
				return true;
		}

		return parent::show_fetchmore($template_id);
	}

	protected function filter_hidden($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS:
			
				return true;
		}

		return parent::filter_hidden($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			// case GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR:

			case GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD:

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST:
			
				return GD_TEMPLATE_FILTER_FARMS;

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:
			
				return GD_TEMPLATE_FILTER_AUTHORFARMS;

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:
			
				return GD_TEMPLATE_FILTER_TAGFARMS;

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:
				
				return GD_TEMPLATE_FILTER_MYFARMS;
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
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:

				$ret = GD_Template_Processor_CustomSectionBlocksUtils::get_author_dataloadsource($template_id);
				break;

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

				$ret = GD_Template_Processor_CustomSectionBlocksUtils::get_tag_dataloadsource($template_id);
				break;

			default:

				$ret = parent::get_dataload_source($template_id, $atts);
				break;
		}

		// Add the format attr
		$tables = array(
			GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT,
		);
		$details = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,			
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,			
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,			
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST,
		);
		$typeaheads = array(
			GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD,
		);
		if (in_array($template_id, $tables)) {
			
			$format = GD_TEMPLATEFORMAT_TABLE;
		}
		elseif (in_array($template_id, $details)) {
			
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
		elseif (in_array($template_id, $typeaheads)) {
			
			$format = GD_TEMPLATEFORMAT_TYPEAHEAD;
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
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:
			
				if ($page = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_AUTHOR)) {

					return $page;
				}
				break;

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:
			
				if ($page = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_TAG)) {

					return $page;
				}
				break;
		}
	
		return parent::get_block_page($template_id);
	}

	protected function get_dataload_query_args($template_id, $atts) {

		$ret = parent::get_dataload_query_args($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS:

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:

				$ret['cat'] = POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS;
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:
			
				$ret['post-status'] = array('publish', 'draft', 'pending'); // Any post type
				break;
		}

		$simpleviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,			
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW,			
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,			
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW,			
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
		);

		// Bring only 3 items for the fullview
		if (in_array($template_id, $fullviews) || in_array($template_id, $simpleviews)) {
			
			$ret['limit'] = 6;
		}

		// Allow to override the limit by $atts (eg: for the Website Features, Filter section)
		if ($limit = $this->get_att($template_id, $atts, 'limit')) {
			$ret['limit'] = $limit;
		}

		return $ret;
	}

	protected function get_runtime_dataload_query_args($template_id, $atts) {

		$ret = parent::get_runtime_dataload_query_args($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:
			
				$ret['author'] = get_current_user_id(); // Logged-in author
				break;

			// Filter by the Profile/Community
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:

				GD_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_authorcontent($ret);
				break;

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

				GD_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_tagcontent($ret);
				break;
		}

		$simpleviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,			
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW,			
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,			
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW,			
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
		);

		// Bring only 3 items for the fullview
		if (in_array($template_id, $fullviews) || in_array($template_id, $simpleviews)) {
			
			$ret['limit'] = 6;
		}

		// Allow to override the limit by $atts (eg: for the Website Features, Filter section)
		if ($limit = $this->get_att($template_id, $atts, 'limit')) {
			$ret['limit'] = $limit;
		}

		return $ret;
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:
			
				// Allow URE to add the ContentSource switch
				return GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHORPOSTLIST;

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

				return GD_TEMPLATE_CONTROLGROUP_BLOCKPOSTLIST;

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:

				return GD_TEMPLATE_CONTROLGROUP_MYBLOCKPOSTLIST;
		}

		return parent::get_controlgroup_top($template_id);
	}

	function get_latestcount_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST:

				return GD_TEMPLATE_LATESTCOUNT_FARMS;

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:

				return GD_TEMPLATE_LATESTCOUNT_AUTHOR_FARMS;

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

				return GD_TEMPLATE_LATESTCOUNT_TAG_FARMS;
		}

		return parent::get_latestcount_template($template_id);
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS:

			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:

			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

				return GD_TEMPLATE_MESSAGEFEEDBACK_FARMS;

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_MYFARMS;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_messagefeedback_position($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST:
			
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:
			
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:
			
				return 'bottom';
		}

		return parent::get_messagefeedback_position($template_id);
	}

	protected function get_iohandler($template_id) {
		
		return GD_DATALOAD_IOHANDLER_LIST;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL:
			case GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST:

				return GD_DATALOADER_POSTLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		// Set the display configuration
		$navigators = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR,
		);
		$addons = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS,
		);
		$details = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,			
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,			
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW,			
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,			
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW,			
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST,
		);
		$tables = array(
			GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT,
		);
		$typeaheads = array(
			GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD,
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
		elseif (in_array($template_id, $tables)) {
			
			$format = GD_TEMPLATEFORMAT_TABLE;
		}
		elseif (in_array($template_id, $typeaheads)) {
			
			$format = GD_TEMPLATEFORMAT_TYPEAHEAD;
		}

		if ($format) {
			$ret['iohandler-atts'][GD_URLPARAM_FORMAT] = $format;
		}
		
		return $ret;
	}

	function block_requires_user_state($template_id, $atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
			case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:

				return true;
		}

		return parent::block_requires_user_state($template_id, $atts);
	}
	// function get_block_jsmethod($template_id, $atts) {

	// 	$ret = parent::get_block_jsmethod($template_id, $atts);
		
	// 	switch ($template_id) {
		
	// 		case GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT:
	// 		case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW:
	// 		case GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW:

	// 			// Only reload/destroy if these are main blocks
	// 			if ($this->get_att($template_id, $atts, 'is-mainblock')) {
	// 				$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
	// 				$this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');
	// 			}
	// 			break;
	// 	}

	// 	return $ret;
	// }

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		// Needed for restraining to 600px together with class pop-outerblock
		$tables = array(
			GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT,
		);
		$feeds = array(
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,

			GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,

			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,

			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_TAGFARMS_SCROLL_LIST,
		);
		if (in_array($template_id, $tables)) {
			$class = 'tableblock';
		}
		elseif (in_array($template_id, $feeds)) {
			$class = 'feed';
		}
		if ($class) {
			$this->append_att($template_id, $atts, 'class', $class);
		}
			
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_SectionBlocks();