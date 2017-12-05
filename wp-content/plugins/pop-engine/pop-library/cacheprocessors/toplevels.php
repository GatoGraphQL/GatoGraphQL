<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_VarsHashProcessor_Hierarchy extends GD_Template_VarsHashProcessor {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TOPLEVEL_HOME,
			GD_TEMPLATE_TOPLEVEL_TAG,
			GD_TEMPLATE_TOPLEVEL_PAGE,
			GD_TEMPLATE_TOPLEVEL_SINGLE,
			GD_TEMPLATE_TOPLEVEL_AUTHOR,
			GD_TEMPLATE_TOPLEVEL_404,
		);
	}

	protected function get_vars_hash_id_by_toplevel($template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$filename = '';
		switch ($template_id) {

			case GD_TEMPLATE_TOPLEVEL_PAGE:

				// Each page is an independent configuration
				$post = $vars['global-state']['post']/*global $post*/;
				// We add the page path to help understand what file it is, in addition to the ID (to make sure to make the configuration unique to that page)
				$filename = 'page_'.str_replace(array('-', '/'), '', GD_TemplateManager_Utils::get_page_path($post->ID)).$post->ID;
				break;

			case GD_TEMPLATE_TOPLEVEL_HOME:

				// Home is pretty much unique
				$filename = 'home';
				break;

			case GD_TEMPLATE_TOPLEVEL_TAG:

				// Tag is pretty much unique
				$filename = 'tag';
				break;

			case GD_TEMPLATE_TOPLEVEL_AUTHOR:

				// Author: depends on its role
				$author = $vars['global-state']['author']/*global $author*/;
				$filename = 'author_'.str_replace('-', '', get_the_user_role($author));
				break;

			case GD_TEMPLATE_TOPLEVEL_SINGLE:

				// Single depends on its post_type and category
				// Past Event and (Upcoming) Event will be different because they have a different (main) category artificially associated to them
				// Project/Event volunteering: 
				$post = $vars['global-state']['post']/*global $post*/;
				$filename = 'single_'.$post->post_type;
				$filename = $this->add_categories($filename, $post);
				break;

			case GD_TEMPLATE_TOPLEVEL_404:

				// 404 is pretty much unique
				// Comment Leo 12/04/2017: calling it "404" fails, must use letters
				$filename = 'fourohfour';
				break;
		}

		return $filename;
	}

	protected function add_categories($filename, $post) {

		if ($post->post_type == 'post') {

			$cats = get_the_category($post->ID);
			foreach ($cats as $cat) {
				$filename .= '_'.str_replace('-', '', $cat->slug).$cat->term_id;
			}
		}

		// Allow for plug-ins to add their own categories. Eg: Events
		$filename = apply_filters('GD_Template_VarsHashProcessor:add_categories', $filename, $post);

		return $filename;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_VarsHashProcessor_Hierarchy();
