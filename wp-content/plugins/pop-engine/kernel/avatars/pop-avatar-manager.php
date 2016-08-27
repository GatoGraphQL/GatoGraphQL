<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Avatars
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Avatars_Manager {

	var $sizes;

	public function __construct() {

		add_action('init', array($this, 'init'));
	}

	public function init() {

		$this->sizes = apply_filters('gd_avatar_thumb_sizes', array());
	}

	public function get_sizes() {
	
		return $this->sizes;
	}

	public function get_names() {
	
		return array_map(array($this, 'get_name'), $this->sizes);
	}

	public function get_name($size) {

		return 'avatar-'.$size;
	}

	public function get_size($name) {

		return substr($name, strlen('avatar-'));
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_avatars_manager;
$gd_avatars_manager = new GD_Avatars_Manager();

