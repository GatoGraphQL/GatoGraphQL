<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Media Library Assistant plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_MLA_MEDIA_TAXONOMY', 'attachment_category');
define ('GD_MLA_MEDIA_TAX_OPERATOR', 'tax_operator');

define ('GD_MEDIA_TYPE_VIDEO_KEY', 'video');
define ('GD_MEDIA_TYPE_IMAGE_KEY', 'image');
define ('GD_MEDIA_TYPE_AUDIO_KEY', 'audio');
define ('GD_MEDIA_TYPE_DOCS_KEY', 'docs');
define ('GD_MEDIA_TYPE_ALL_KEY', 'all');

define ('GD_MEDIA_TYPE_VIDEO', 'video');
define ('GD_MEDIA_TYPE_IMAGE', 'image');
define ('GD_MEDIA_TYPE_AUDIO', 'audio');
define ('GD_MEDIA_TYPE_DOCS', 'application/pdf,application/*ms*,application/*officedocument*,application/rtf');
define ('GD_MEDIA_TYPE_ALL', GD_MEDIA_TYPE_VIDEO.','.GD_MEDIA_TYPE_IMAGE.','.GD_MEDIA_TYPE_AUDIO.','.GD_MEDIA_TYPE_DOCS);

function gd_get_media_type_value($media_type_key) {

	$post_mime_key_value = array(
		GD_MEDIA_TYPE_VIDEO_KEY => GD_MEDIA_TYPE_VIDEO,
		GD_MEDIA_TYPE_IMAGE_KEY => GD_MEDIA_TYPE_IMAGE,
		GD_MEDIA_TYPE_AUDIO_KEY => GD_MEDIA_TYPE_AUDIO,
		GD_MEDIA_TYPE_DOCS_KEY => GD_MEDIA_TYPE_DOCS
	);
	
	return $post_mime_key_value[$media_type_key];
}

function gd_resources_media_taxonomy() { return apply_filters('gd_resources_media_taxonomy', ''); }
