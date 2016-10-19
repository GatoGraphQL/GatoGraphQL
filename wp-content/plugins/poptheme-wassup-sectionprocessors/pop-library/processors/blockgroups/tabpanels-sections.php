<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_PROJECTS', PoP_ServerUtils::get_template_definition('blockgroup-projects-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_STORIES', PoP_ServerUtils::get_template_definition('blockgroup-stories-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('blockgroup-announcements-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_DISCUSSIONS', PoP_ServerUtils::get_template_definition('blockgroup-discussions-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_FEATURED', PoP_ServerUtils::get_template_definition('blockgroup-featured-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_BLOG', PoP_ServerUtils::get_template_definition('blockgroup-blog-tabpanel'));

// My Content
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPROJECTS', PoP_ServerUtils::get_template_definition('blockgroup-myprojects-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYSTORIES', PoP_ServerUtils::get_template_definition('blockgroup-mystories-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('blockgroup-myannouncements-tabpanel'));
define ('GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('blockgroup-mydiscussions-tabpanel'));

class GD_Custom_Template_Processor_SectionTabPanelBlockGroups extends GD_Template_Processor_SectionTabPanelBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_PROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_STORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_ANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_DISCUSSIONS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_FEATURED,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_BLOG,

			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYSTORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYDISCUSSIONS,
		);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPROJECTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYSTORIES:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYANNOUNCEMENTS:
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYDISCUSSIONS:

				// Only reload/destroy if these are main blocks
				if ($this->get_att($template_id, $atts, 'is-mainblock')) {
					$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
					$this->add_jsmethod($ret, 'refetchBlockGroupOnUserLoggedIn');
				}
				break;
		}

		return $ret;
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PROJECTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_LIST,
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLLMAP,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_STORIES:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ANNOUNCEMENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_DISCUSSIONS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_FEATURED:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_LIST,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_BLOG:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_BLOG_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_BLOG_SCROLL_FULLVIEW,
						GD_TEMPLATE_BLOCK_BLOG_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_BLOG_SCROLL_LIST,
						// GD_TEMPLATE_BLOCK_BLOG_SCROLL_THUMBNAIL,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPROJECTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYPROJECTS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYPROJECTS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYPROJECTS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYSTORIES:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYSTORIES_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYSTORIES_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYSTORIES_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYANNOUNCEMENTS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYDISCUSSIONS:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_BLOCK_MYDISCUSSIONS_TABLE_EDIT,
						GD_TEMPLATE_BLOCK_MYDISCUSSIONS_SCROLL_SIMPLEVIEWPREVIEW,
						GD_TEMPLATE_BLOCK_MYDISCUSSIONS_SCROLL_FULLVIEWPREVIEW,
					)
				);
				break;
		}

		return $ret;
	}

	function get_panel_headers($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PROJECTS:

				return array(
					GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_LIST,
					),
					GD_TEMPLATE_BLOCK_PROJECTS_SCROLLMAP => array(),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_STORIES:

				return array(
					GD_TEMPLATE_BLOCK_STORIES_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_STORIES_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_STORIES_SCROLL_LIST,
					),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ANNOUNCEMENTS:

				return array(
					GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_LIST,
					),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_DISCUSSIONS:

				return array(
					GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_LIST,
					),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_FEATURED:

				return array(
					GD_TEMPLATE_BLOCK_FEATURED_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_FEATURED_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_FEATURED_SCROLL_LIST,
					),
				);

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_BLOG:

				return array(
					GD_TEMPLATE_BLOCK_BLOG_SCROLL_SIMPLEVIEW => array(
						GD_TEMPLATE_BLOCK_BLOG_SCROLL_SIMPLEVIEW,
						GD_TEMPLATE_BLOCK_BLOG_SCROLL_FULLVIEW,
					),
					GD_TEMPLATE_BLOCK_BLOG_SCROLL_LIST => array(
						GD_TEMPLATE_BLOCK_BLOG_SCROLL_DETAILS,
						GD_TEMPLATE_BLOCK_BLOG_SCROLL_LIST,
					),
				);
		}

		return parent::get_panel_headers($template_id, $atts);
	}

	protected function get_controlgroup_top($template_id) {

		// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PROJECTS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_STORIES:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ANNOUNCEMENTS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_DISCUSSIONS:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_FEATURED:
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_BLOG:

					return GD_TEMPLATE_CONTROLGROUP_POSTLIST;
				
				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPROJECTS:

					return GD_TEMPLATE_CONTROLGROUP_MYPROJECTLIST;

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYSTORIES:

					return GD_TEMPLATE_CONTROLGROUP_MYSTORYLIST;

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYANNOUNCEMENTS:

					return GD_TEMPLATE_CONTROLGROUP_MYANNOUNCEMENTLIST;

				case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYDISCUSSIONS:

					return GD_TEMPLATE_CONTROLGROUP_MYDISCUSSIONLIST;
			}
		}

		return parent::get_controlgroup_top($template_id);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_PROJECTS:

				return GD_TEMPLATE_FILTER_PROJECTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPROJECTS:

				return GD_TEMPLATE_FILTER_MYPROJECTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_STORIES:
			
				return GD_TEMPLATE_FILTER_STORIES;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYSTORIES:
			
				return GD_TEMPLATE_FILTER_MYSTORIES;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_ANNOUNCEMENTS:
			
				return GD_TEMPLATE_FILTER_ANNOUNCEMENTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYANNOUNCEMENTS:
			
				return GD_TEMPLATE_FILTER_MYANNOUNCEMENTS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_DISCUSSIONS:
			
				return GD_TEMPLATE_FILTER_DISCUSSIONS;

			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYDISCUSSIONS:
			
				return GD_TEMPLATE_FILTER_MYDISCUSSIONS;
			
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_FEATURED:
			
				return GD_TEMPLATE_FILTER_FEATURED;
				
			case GD_TEMPLATE_BLOCKGROUP_TABPANEL_BLOG:
			
				return GD_TEMPLATE_FILTER_BLOG;
		}
		
		return parent::get_filter_template($template_id);
	}

	function get_panel_header_fontawesome($blockgroup, $blockunit) {

		$details = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_BLOG_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_BLOG_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_BLOG_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_THUMBNAIL,
			// GD_TEMPLATE_BLOCK_BLOG_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_BLOG_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLLMAP,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYPROJECTS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYSTORIES_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYDISCUSSIONS_TABLE_EDIT,
		);
		$simpleviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYPROJECTS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYSTORIES_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYDISCUSSIONS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYPROJECTS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYSTORIES_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYDISCUSSIONS_SCROLL_FULLVIEWPREVIEW,
		);

		if (in_array($blockunit, $details)) {

			return 'fa-th-list';
		}
		elseif (in_array($blockunit, $simpleviews) || in_array($blockunit, $simpleviewpreviews)) {
			
			return 'fa-angle-right';
		}
		elseif (in_array($blockunit, $fullviews) || in_array($blockunit, $fullviewpreviews)) {
			
			return 'fa-angle-double-right';
		}
		elseif (in_array($blockunit, $thumbnails)) {
			
			return 'fa-th';
		}
		elseif (in_array($blockunit, $lists)) {
			
			return 'fa-list';
		}
		elseif (in_array($blockunit, $maps)) {
			
			return 'fa-map-marker';
		}
		elseif (in_array($blockunit, $edits)) {
			
			return 'fa-edit';
		}
		// elseif (in_array($blockunit, $fullviewpreviews)) {
			
		// 	return 'fa-eye';
		// }

		return parent::get_panel_header_fontawesome($blockgroup, $blockunit);
	}
	function get_panel_header_title($blockgroup, $blockunit, $atts) {

		$details = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_DETAILS,
			GD_TEMPLATE_BLOCK_BLOG_SCROLL_DETAILS,
		);
		$simpleviews = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_SIMPLEVIEW,
			GD_TEMPLATE_BLOCK_BLOG_SCROLL_SIMPLEVIEW,
		);
		$fullviews = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_FULLVIEW,
			GD_TEMPLATE_BLOCK_BLOG_SCROLL_FULLVIEW,
		);
		$thumbnails = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_THUMBNAIL,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_THUMBNAIL,
			// GD_TEMPLATE_BLOCK_BLOG_SCROLL_THUMBNAIL,
		);
		$lists = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_LIST,
			GD_TEMPLATE_BLOCK_BLOG_SCROLL_LIST,
		);
		$maps = array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLLMAP,
		);
		$edits = array(
			GD_TEMPLATE_BLOCK_MYPROJECTS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYSTORIES_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_TABLE_EDIT,
			GD_TEMPLATE_BLOCK_MYDISCUSSIONS_TABLE_EDIT,
		);
		$simpleviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYPROJECTS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYSTORIES_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_SCROLL_SIMPLEVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYDISCUSSIONS_SCROLL_SIMPLEVIEWPREVIEW,
		);
		$fullviewpreviews = array(
			GD_TEMPLATE_BLOCK_MYPROJECTS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYSTORIES_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYANNOUNCEMENTS_SCROLL_FULLVIEWPREVIEW,
			GD_TEMPLATE_BLOCK_MYDISCUSSIONS_SCROLL_FULLVIEWPREVIEW,
		);

		if (in_array($blockunit, $details)) {

			return __('Details', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $simpleviews) || in_array($blockunit, $simpleviewpreviews)) {
			
			return __('Feed', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $fullviews) || in_array($blockunit, $fullviewpreviews)) {
			
			return __('Extended', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $thumbnails)) {
			
			return __('Thumbnail', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $lists)) {
			
			return __('List', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $maps)) {
			
			return __('Map', 'poptheme-wassup-sectionprocessors');
		}
		elseif (in_array($blockunit, $edits)) {
			
			return __('Edit', 'poptheme-wassup-sectionprocessors');
		}
		// elseif (in_array($blockunit, $fullviewpreviews)) {
			
		// 	return __('View/Preview', 'poptheme-wassup-sectionprocessors');
		// }

		return parent::get_panel_header_title($blockgroup, $blockunit, $atts);
	}

	function init_atts($template_id, &$atts) {

		$class = '';
		$feeds = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_PROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_STORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_ANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_DISCUSSIONS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_FEATURED,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_BLOG,
		);
		$tables = array(
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPROJECTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYSTORIES,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYANNOUNCEMENTS,
			GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYDISCUSSIONS,
		);
		if (in_array($template_id, $feeds)) {
			$class = 'feed';
		}
		elseif (in_array($template_id, $tables)) {
			$class = 'tableblock';
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
new GD_Custom_Template_Processor_SectionTabPanelBlockGroups();
