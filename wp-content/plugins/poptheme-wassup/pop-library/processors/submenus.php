<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBMENU_AUTHOR', PoP_ServerUtils::get_template_definition('submenu-author'));
define ('GD_TEMPLATE_SUBMENU_TAG', PoP_ServerUtils::get_template_definition('submenu-tag'));
define ('GD_TEMPLATE_SUBMENU_SINGLE', PoP_ServerUtils::get_template_definition('submenu-single'));

class GD_Template_Processor_CustomSubMenus extends GD_Template_Processor_SubMenusBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBMENU_AUTHOR,
			GD_TEMPLATE_SUBMENU_TAG,
			GD_TEMPLATE_SUBMENU_SINGLE,
		);
	}

	// function get_header_type($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_SUBMENU_AUTHOR:
	// 		case GD_TEMPLATE_SUBMENU_SINGLE:

	// 			return 'btn-group';
	// 	}

	// 	return parent::get_header_type($template_id);
	// }	
	// function get_class($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_SUBMENU_AUTHOR:
	// 		case GD_TEMPLATE_SUBMENU_SINGLE:

	// 			return 'btn-group';
	// 	}

	// 	return parent::get_class($template_id);
	// }	
	function get_blockunititem_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_AUTHOR:
			case GD_TEMPLATE_SUBMENU_TAG:
			case GD_TEMPLATE_SUBMENU_SINGLE:

				return 'btn btn-default btn-sm';
		}

		return parent::get_blockunititem_class($template_id);
	}
	function get_blockunititem_xs_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_AUTHOR:
			case GD_TEMPLATE_SUBMENU_TAG:
			case GD_TEMPLATE_SUBMENU_SINGLE:

				return 'btn btn-default btn-sm btn-block';
		}

		return parent::get_blockunititem_class($template_id);
	}
	function get_blockunititem_dropdown_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_AUTHOR:
			case GD_TEMPLATE_SUBMENU_TAG:
			case GD_TEMPLATE_SUBMENU_SINGLE:

				return 'btn-default';
		}

		return parent::get_blockunititem_dropdown_class($template_id);
	}

	function get_blockunititems($template_id, $atts) {

		global $gd_template_settingsmanager;

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_AUTHOR:

				// Potentially, add an extra header level if the current page is one of the subheaders
				$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
				$current_blockgroup = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_AUTHOR);
				
				$ret = array();
				
				// Description
				// $ret[GD_TEMPLATE_BLOCKGROUP_AUTHORDESCRIPTION] = array();
				$main_subheaders = array(
					GD_TEMPLATE_BLOCKGROUP_AUTHORDESCRIPTION,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORRECOMMENDEDPOSTS,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWERS,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWINGUSERS,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSUBSCRIBEDTOTAGS,
				);
				$ret[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORMAINALLCONTENT] = $main_subheaders;
				if (in_array($current_blockgroup, $main_subheaders)) {
					$ret[$current_blockgroup] = array();	
				}

				// // All Content
				// $content_subheaders = array();
				// // if (POPTHEME_WASSUP_PAGE_WEBPOSTLINKS) {
				// // 	$content_subheaders[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORLINKS;
				// // }
				// if (POPTHEME_WASSUP_PAGE_WEBPOSTS) {
				// 	$content_subheaders[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORWEBPOSTS;
				// }
				// // if (POPTHEME_WASSUP_PAGE_HIGHLIGHTS) {
				// // 	$content_subheaders[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORHIGHLIGHTS;
				// // }
				// $ret[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORALLCONTENT] = $content_subheaders;
				// if (in_array($current_blockgroup, $content_subheaders)) {

				// 	$ret[$current_blockgroup] = array();
				// }

				if (POPTHEME_WASSUP_PAGE_WEBPOSTS) {
					$ret[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORWEBPOSTS] = array();
				}

				// Allow for the members tab to be added by User Role Editor plugin
				return apply_filters('GD_Template_Processor_CustomSubMenus:author:blockgroupitems', $ret, $current_blockgroup);

			case GD_TEMPLATE_SUBMENU_TAG:

				// Potentially, add an extra header level if the current page is one of the subheaders
				$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
				$current_blockgroup = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_TAG);

				$ret = array();
				
				$main_subheaders = array(
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSUBSCRIBERS,
				);
				$ret[GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGMAINALLCONTENT] = $main_subheaders;
				if (in_array($current_blockgroup, $main_subheaders)) {
					$ret[$current_blockgroup] = array();	
				}

				if (POPTHEME_WASSUP_PAGE_WEBPOSTS) {
					$ret[GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGWEBPOSTS] = array();
				}

				return apply_filters('GD_Template_Processor_CustomSubMenus:tag:blockgroupitems', $ret, $current_blockgroup);

			case GD_TEMPLATE_SUBMENU_SINGLE:

				// Potentially, add an extra header level if the current page is one of the subheaders
				$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
				$current_blockgroup = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_SINGLE);
				
				$ret = array();
				
				$main_subheaders = array(
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT,
				);
				$ret[GD_TEMPLATE_BLOCKGROUP_SINGLEPOST] = $main_subheaders;
				if (in_array($current_blockgroup, $main_subheaders)) {
					$ret[$current_blockgroup] = array();	
				}

				$ret[GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT] = array();
				$ret[GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS] = array();
				$ret[GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY] = array();

				return apply_filters('GD_Template_Processor_CustomSubMenus:single:blockgroupitems', $ret, $current_blockgroup);
		}

		return parent::get_blockunititems($template_id, $atts);
	}

	function get_blockunititem_title($template_id, $blockunit) {

		global $gd_template_settingsmanager;
		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_AUTHOR:
			case GD_TEMPLATE_SUBMENU_TAG:
			case GD_TEMPLATE_SUBMENU_SINGLE:

				$page_id = $gd_template_settingsmanager->get_blockgroup_page($blockunit);
				return get_the_title($page_id);
		}
	
		return parent::get_blockunititem_title($template_id, $blockunit);
	}

	function get_blockunititem_url($template_id, $blockunit) {

		global $gd_template_settingsmanager;
		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_AUTHOR:

				global $author;
				$url = get_author_posts_url($author);
				$page_id = $gd_template_settingsmanager->get_blockgroup_page($blockunit, GD_SETTINGS_HIERARCHY_AUTHOR);

				$url = GD_TemplateManager_Utils::add_tab($url, $page_id);

				// Allow URE to add the Organization/Community content source attribute
				$url = apply_filters('GD_Template_Processor_CustomSubMenus:get_blockunititem_url:author', $url, $author, $blockunit);
				return $url;

			case GD_TEMPLATE_SUBMENU_TAG:

				$url = get_tag_link(get_queried_object_id());
				$page_id = $gd_template_settingsmanager->get_blockgroup_page($blockunit, GD_SETTINGS_HIERARCHY_TAG);

				$url = GD_TemplateManager_Utils::add_tab($url, $page_id);

				$url = apply_filters('GD_Template_Processor_CustomSubMenus:get_blockunititem_url:tag', $url, $blockunit);
				return $url;

			case GD_TEMPLATE_SUBMENU_SINGLE:

				global $post;
				$url = get_permalink($post->ID);
				$page_id = $gd_template_settingsmanager->get_blockgroup_page($blockunit, GD_SETTINGS_HIERARCHY_SINGLE);
				return GD_TemplateManager_Utils::add_tab($url, $page_id);
		}
	
		return parent::get_blockunititem_url($template_id, $blockunit);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomSubMenus();
