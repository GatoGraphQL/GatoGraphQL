<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Avatar Implementations
 * ---------------------------------------------------------------------------------------------------------------*/

// 26: Wordpress Top Bar Login Menu
define('GD_AVATAR_SIZE_26', 26);

define('GD_AVATAR_SIZE_60', 60);
define('GD_AVATAR_SIZE_82', 82);
define('GD_AVATAR_SIZE_120', 120);

// Define what sizes to generate the avatar file
add_filter('gd_avatar_thumb_sizes', 'popcore_custom_avatar_thumb_sizes');
function popcore_custom_avatar_thumb_sizes($sizes) {

	return array_merge(
		$sizes,
		array(
			GD_AVATAR_SIZE_26,
			GD_AVATAR_SIZE_60,
			GD_AVATAR_SIZE_82,
			GD_AVATAR_SIZE_120,
		)
	);
}