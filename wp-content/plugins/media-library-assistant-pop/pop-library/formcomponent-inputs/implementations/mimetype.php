<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_MimeType extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values[GD_MEDIA_TYPE_VIDEO_KEY] = __('Video', 'greendrinks');
		$values[GD_MEDIA_TYPE_IMAGE_KEY] = __('Images', 'greendrinks');
		$values[GD_MEDIA_TYPE_AUDIO_KEY] = __('Podcasts', 'greendrinks');
		$values[GD_MEDIA_TYPE_DOCS_KEY] = __('Documents', 'greendrinks');
		
		return $values;
	}	
	
	function get_default_value() {
	
		return array(GD_MEDIA_TYPE_ALL_KEY);
	}		
}
