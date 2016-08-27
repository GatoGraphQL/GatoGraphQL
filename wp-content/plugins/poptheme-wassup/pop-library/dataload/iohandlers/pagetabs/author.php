<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TABS_AUTHOR', 'tabs-author');

class GD_DataLoad_TabIOHandler_Author extends GD_DataLoad_TabIOHandler {

    function get_name() {

		return GD_DATALOAD_IOHANDLER_TABS_AUTHOR;
	}

	protected function get_thumb() {
		global $author;
		$avatar = gd_get_avatar($author, GD_AVATAR_SIZE_16);
		return array(
			'src' => $avatar['src'],
			'w' => $avatar['size'],
			'h' => $avatar['size']
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

		global $author;
		return get_the_author_meta( 'display_name', $author );
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_TabIOHandler_Author();