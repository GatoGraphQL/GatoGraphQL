<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Avatar Implementations
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Pagetabs
define('GD_AVATAR_SIZE_16', 16);
// 24: Wordpress Comments
define('GD_AVATAR_SIZE_24', 24);
// 32: Wordpress "All Users" page
define('GD_AVATAR_SIZE_32', 32);
// 40: Google Maps
define('GD_AVATAR_SIZE_40', 40);
// 50: WP Comments in Dashboard
define('GD_AVATAR_SIZE_50', 50);
// 64: Wordpress Top Bar Login Menu
define('GD_AVATAR_SIZE_64', 64);

// This is the big avatar, it will always crop to this size so it's safe to keep it under library/
define('GD_AVATAR_SIZE_150', 150);

// Define what sizes to generate the avatar file
add_filter('gd_avatar_thumb_sizes', 'gd_avatar_thumb_sizes_impl');
function gd_avatar_thumb_sizes_impl($sizes) {

	return array_merge(
		$sizes,
		array(
			GD_AVATAR_SIZE_16,
			GD_AVATAR_SIZE_24,
			GD_AVATAR_SIZE_32,
			GD_AVATAR_SIZE_40,
			GD_AVATAR_SIZE_50,
			GD_AVATAR_SIZE_64,
			GD_AVATAR_SIZE_150
		)
	);
}
