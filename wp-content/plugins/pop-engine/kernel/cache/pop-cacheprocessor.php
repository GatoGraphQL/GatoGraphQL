<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_CacheProcessor {

	function __construct() {

		add_action('init', array($this, 'init'));
	}

	function init() {

		global $gd_template_cacheprocessor_manager;
		$gd_template_cacheprocessor_manager->add($this, $this->get_templates_to_process());
	}

	function get_templates_to_process() {
	
		return array();
	}

	protected function add_categories($filename, $post) {

		if ($post->post_type == 'post') {

			$cats = get_the_category($post->ID);
			foreach ($cats as $cat) {
				$filename .= '_'.str_replace('-', '', $cat->slug).$cat->term_id;
			}
		}

		// Allow for plug-ins to add their own categories. Eg: Events
		$filename = apply_filters('GD_Template_CacheProcessor:add_categories', $filename, $post);

		return $filename;
	}

	function get_cache_filename($template_id) {

		// Return the filename coded, otherwise it may become too long and produces errors accessing the file
		if ($filename = $this->get_cache_filename_by_toplevel($template_id)) {

			// Do not start with a number (just in case)
			return 'c'.md5($filename);
		}

		return false;
	}

	protected function get_cache_filename_by_toplevel($template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$filename = '';
		switch ($template_id) {

			case GD_TEMPLATE_TOPLEVEL_PAGE:

				// Each page is an independent configuration
				$post = $vars['global-state']['post']/*global $post*/;
				global $gd_dataquery_manager;
				// We add the page path to help understand what file it is, in addition to the ID (to make sure to make the configuration unique to that page)
				$filename = 'page_'.str_replace(array('-', '/'), '', GD_TemplateManager_Utils::get_page_path($post->ID)).$post->ID;

				// // Special pages: dataqueries' cacheablepages serve layouts, noncacheable pages serve fields.
				// // So the settings for these pages depend on the URL params
				// if (in_array($post->ID, $gd_dataquery_manager->get_noncacheablepages())) {

				// 	// if ($fields = $_REQUEST['fields']) {
				// 	if ($fields = $vars['fields']) {
				// 		// if (!is_array($fields)) {
				// 		// 	$fields = array($fields);
				// 		// }
				// 		// Do not add the values straight since they are too long, do a hash instead to shorten it
				// 		// $filename .= '-fields_'.sha1(implode('', $fields));
				// 		$filename .= '-'.sha1(implode('', $fields));
				// 	}
				// }
				// elseif (in_array($post->ID, $gd_dataquery_manager->get_cacheablepages())) {

				// 	// if ($layouts = $_REQUEST['layouts']) {
				// 	if ($layouts = $vars['layouts']) {
				// 		// if (!is_array($layouts)) {
				// 		// 	$layouts = array($layouts);
				// 		// }
				// 		// Do not add the values straight since they are too long, do a hash instead to shorten it
				// 		// $filename .= '-layouts_'.sha1(implode('', $layouts));
				// 		$filename .= '-'.sha1(implode('', $layouts));
				// 	}
				// }
				// return $this->add_vars($filename);
				break;

			case GD_TEMPLATE_TOPLEVEL_HOME:

				// Home is pretty much unique
				// return $this->add_vars('home');
				$filename = 'home';
				break;

			case GD_TEMPLATE_TOPLEVEL_TAG:

				// Tag is pretty much unique
				// return $this->add_vars('tag');
				$filename = 'tag';
				break;

			case GD_TEMPLATE_TOPLEVEL_AUTHOR:

				// Author: depends on its role
				$author = $vars['global-state']['author']/*global $author*/;
				$filename = 'author_'.str_replace('-', '', gd_ure_getuserrole($author));
				// return $this->add_vars($filename);
				break;

			case GD_TEMPLATE_TOPLEVEL_SINGLE:

				// Single depends on its post_type and category
				// Past Event and (Upcoming) Event will be different because they have a different (main) category artificially associated to them
				// Project/Event volunteering: 
				$post = $vars['global-state']['post']/*global $post*/;
				$filename = 'single_'.$post->post_type;
				$filename = $this->add_categories($filename, $post);
				// return $this->add_vars($filename);
				break;

			case GD_TEMPLATE_TOPLEVEL_404:

				// 404 is pretty much unique
				// Comment Leo 12/04/2017: calling it "404" fails, must use letters
				// return $this->add_vars('404');
				// return $this->add_vars('fourohfour');
				$filename = 'fourohfour';
				break;
		}

		if ($filename) {

			return $filename.PoP_VarsUtils::get_vars_identifier();
		}

		return false;
	}
}