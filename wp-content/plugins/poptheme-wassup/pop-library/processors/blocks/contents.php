<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_AUTHOR_CONTENT', PoP_ServerUtils::get_template_definition('block-author-content'));
define ('GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT', PoP_ServerUtils::get_template_definition('block-author-summarycontent'));
define ('GD_TEMPLATE_BLOCK_SINGLE_CONTENT', PoP_ServerUtils::get_template_definition('block-single-content'));
define ('GD_TEMPLATE_BLOCK_SINGLEINTERACTION_CONTENT', PoP_ServerUtils::get_template_definition('block-singleinteraction-content'));
define ('GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT', PoP_ServerUtils::get_template_definition('block-singleabout-content'));
define ('GD_TEMPLATE_BLOCK_POSTHEADER', PoP_ServerUtils::get_template_definition('block-postheader'));
define ('GD_TEMPLATE_BLOCK_USERHEADER', PoP_ServerUtils::get_template_definition('block-userheader'));

class GD_Template_Processor_CustomContentBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_AUTHOR_CONTENT,
			GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT,
			GD_TEMPLATE_BLOCK_SINGLE_CONTENT,
			GD_TEMPLATE_BLOCK_SINGLEINTERACTION_CONTENT,
			GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
			GD_TEMPLATE_BLOCK_POSTHEADER,
			GD_TEMPLATE_BLOCK_USERHEADER,
		);
	}

	protected function get_description_bottom($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT:

				global $author;
				$url = get_author_posts_url($author);
				return sprintf(
					'<p class="text-center"><a href="%s">%s</a></p>',
					$url,
					__('Go to Full Profile ', 'poptheme-wassup').'<i class="fa fa-fw fa-arrow-right"></i>'
				);
		}

		return parent::get_description_bottom($template_id, $atts);
	}
	
	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHOR_CONTENT:
			case GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT:

				global $author;
				return get_the_author_meta('display_name', $author);

			case GD_TEMPLATE_BLOCK_SINGLE_CONTENT:
			case GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT:

				global $post;
				return get_the_title($post->ID);

			case GD_TEMPLATE_BLOCK_POSTHEADER:
			case GD_TEMPLATE_BLOCK_USERHEADER:

				return '';
		}
		
		return parent::get_title($template_id);
	}

	protected function get_sidebars_by_category() {

		return apply_filters(
			'GD_Template_Processor_CustomContentBlocks:get_block_inner_templates:content:sidebar_by_category',
			array()
		);
	}
	protected function get_bottomsidebars_by_category() {

		return apply_filters(
			'GD_Template_Processor_CustomContentBlocks:get_block_inner_templates:content:bottomsidebar_by_category',
			array()
		);
	}
	
	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT:
			case GD_TEMPLATE_BLOCK_AUTHOR_CONTENT:

				// Add the Sidebar on the top
				global $author;
				if (gd_ure_is_organization($author)) {

					$ret[] = GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION;
				}
				elseif (gd_ure_is_individual($author)) {
					
					$ret[] = GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL;
				}

				// Show the Author Description inside the widget instead of the body?
				if (PoPTheme_Wassup_Utils::author_fulldescription()) {
					$ret[] = GD_TEMPLATE_CONTENT_AUTHOR;
				}
				break;

			case GD_TEMPLATE_BLOCK_SINGLE_CONTENT:

				// Add the Sidebar on the top
				$post_type = get_post_type();
				$cat = gd_get_the_main_category();
				if ($post_type == EM_POST_TYPE_EVENT) {

					$ret[] = gd_em_single_event_is_future() ? GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT : GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT;
				}
				elseif ($post_type == 'post') {

					$cats_sidebar = $this->get_sidebars_by_category();
					if ($cat_sidebar = $cats_sidebar[$cat]) {
						$ret[] = $cat_sidebar;
					}
				}

				$ret[] = GD_TEMPLATE_CONTENT_SINGLE;

				if ($post_type == EM_POST_TYPE_EVENT) {

					$ret[] = GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
				}
				elseif ($post_type == 'post') {

					$cats_sidebar = $this->get_bottomsidebars_by_category();
					if ($cat_sidebar = $cats_sidebar[$cat]) {
						$ret[] = $cat_sidebar;
					}
				}

				// $ret[] = GD_TEMPLATE_CONTENT_USERPOSTINTERACTION;
				break;

			case GD_TEMPLATE_BLOCK_SINGLEINTERACTION_CONTENT:

				$ret[] = GD_TEMPLATE_CONTENT_USERPOSTINTERACTION;
				break;

			case GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT:

				$ret[] = GD_TEMPLATE_CONTENT_SINGLE;
				break;

			case GD_TEMPLATE_BLOCK_POSTHEADER:

				$ret[] = GD_TEMPLATE_CONTENT_POSTHEADER;
				break;

			case GD_TEMPLATE_BLOCK_USERHEADER:

				$ret[] = GD_TEMPLATE_CONTENT_USERHEADER;
				break;
		}
	
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT:

				$this->append_att($template_id, $atts, 'class', 'block-singleabout-content');
				$inners = $this->get_block_inner_templates($template_id);
				foreach ($inners as $inner) {
					$this->append_att($inner, $atts, 'class', 'col-xs-12');
				}
				break;
		}

		// Because the print already prints the sideinfo, must hide the compactsidebar
		// $sidebar = '';
		switch ($template_id) {

			// case GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT:
			// case GD_TEMPLATE_BLOCK_AUTHOR_CONTENT:

			// 	global $author;
			// 	if (gd_ure_is_organization($author)) {

			// 		$sidebar = GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION;
			// 	}
			// 	elseif (gd_ure_is_individual($author)) {
					
			// 		$sidebar = GD_TEMPLATE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL;
			// 	}
				// break;

			case GD_TEMPLATE_BLOCK_SINGLE_CONTENT:

				$this->append_att($template_id, $atts, 'class', 'block-single-content');

				// Also append the post_status, so we can hide the bottomsidebar for draft posts
				global $post;
				$this->append_att($template_id, $atts, 'runtime-class', $post->post_type.'-'.$post->ID);
				$this->append_att($template_id, $atts, 'runtime-class', get_post_status($post->ID));

				// $post_type = get_post_type();
				// if ($post_type == EM_POST_TYPE_EVENT) {

				// 	$sidebar = gd_em_single_event_is_future() ? GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT : GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT;
				// 	$bottomsidebar = GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
				// }
				// elseif ($post_type == 'post') {

				// 	$cat = gd_get_the_main_category();
				// 	$cats_sidebar = $this->get_sidebars_by_category();
				// 	$sidebar = $cats_sidebar[$cat];
				// 	$cats_sidebar = $this->get_bottomsidebars_by_category();
				// 	$bottomsidebar = $cats_sidebar[$cat];
				// }
				break;

			case GD_TEMPLATE_BLOCK_SINGLEINTERACTION_CONTENT:

				$this->append_att($template_id, $atts, 'class', 'block-singleinteraction-content');
				break;
		}
		// if ($sidebar) {
		// 	$this->append_att($sidebar, $atts, 'class', 'pop-hidden-print');
		// }
		// if ($bottomsidebar) {
		// 	$this->append_att($bottomsidebar, $atts, 'class', 'pop-hidden-print');
		// }
		
		return parent::init_atts($template_id, $atts);
	}

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT:

				$ret['blocksection-inners'] = 'row row-item';
				break;
		}

		return $ret;
	}

	function get_dataload_source($template_id, $atts) {

		global $gd_template_settingsmanager;
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHOR_CONTENT:
			case GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT:

				global $author;
				$url = get_author_posts_url($author);
				$page_id = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_AUTHOR);
				return GD_TemplateManager_Utils::add_tab($url, $page_id);

			case GD_TEMPLATE_BLOCK_SINGLE_CONTENT:
			case GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT:

				global $post;
				$url = get_permalink($post->ID);
				$page_id = $gd_template_settingsmanager->get_block_page($template_id, GD_SETTINGS_HIERARCHY_SINGLE);
				return GD_TemplateManager_Utils::add_tab($url, $page_id);
		}
	
		return parent::get_dataload_source($template_id, $atts);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCK_AUTHOR_CONTENT:
			case GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT:

				return GD_DATALOADER_AUTHOR;

			case GD_TEMPLATE_BLOCK_SINGLE_CONTENT:
			case GD_TEMPLATE_BLOCK_SINGLEINTERACTION_CONTENT:
			case GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT:

				// Decide on the dataloader based on the post_type of the single element
				$post_type = get_post_type();

				if ($post_type == EM_POST_TYPE_EVENT) {
					return GD_DATALOADER_EVENTSINGLE;
				}

				return GD_DATALOADER_SINGLE;
		
			case GD_TEMPLATE_BLOCK_POSTHEADER:

				return GD_DATALOADER_EDITPOST;
		
			case GD_TEMPLATE_BLOCK_USERHEADER:

				return GD_DATALOADER_EDITUSER;
		}
		
		return parent::get_dataloader($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomContentBlocks();