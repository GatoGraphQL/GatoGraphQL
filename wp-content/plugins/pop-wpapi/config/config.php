<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * IDs Configuration
 * Override these definitions to implement the corresponding features
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// CreateUpdate Post Moderation
//--------------------------------------------------------
define ('GD_CONF_CREATEUPDATEPOST_MODERATE', true);

/**---------------------------------------------------------------------------------------------------------------
 * Avatar Implementations
 * ---------------------------------------------------------------------------------------------------------------*/

define('GD_AVATAR_SIZE_100', 100);

// Define what sizes to generate the avatar file
add_filter('gd_avatar_thumb_sizes', 'popengine_avatar_thumb_sizes');
function popengine_avatar_thumb_sizes($sizes) {

	return array_merge(
		$sizes,
		array(
			GD_AVATAR_SIZE_100,
		)
	);
}