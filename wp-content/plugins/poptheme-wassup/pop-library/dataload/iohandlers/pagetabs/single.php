<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_SINGLE', 'tabs-single');

class GD_DataLoad_TabIOHandler_Single extends GD_DataLoad_TabIOHandler {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_SINGLE;
	}

	protected function get_thumb() {
		
		$vars = GD_TemplateManager_Utils::get_vars();
		$post = $vars['global-state']['post']/*global $post*/;
	    $post_thumb_id = gd_get_thumb_id($post->ID);
		$thumb = wp_get_attachment_image_src( $post_thumb_id, 'favicon');
		return array(
			'src' => $thumb[0],
			'w' => $thumb[1],
			'h' => $thumb[2]
		);
	}

	protected function get_pretitle() {

		// If loading a Single with param 'tab' then its loading a particular tab, eg: Related Content
		// If so, change the title
		if ($page_id = GD_TemplateManager_Utils::get_hierarchy_page_id(false)) {
		
			return get_the_title($page_id);
		}
		
		return parent::get_pretitle();
	}
	
	function get_title() {

		return get_the_title();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_Single();