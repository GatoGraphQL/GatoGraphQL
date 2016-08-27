<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Announcements
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_MediaRoot extends GD_Filter {

	function get_filter_args_override_values() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}
		
		$args = parent::get_filter_args_override_values();
						
		// Addition of post mime type
		if ($post_mime_type = $this->get_post_mime_type()) {
			$args['post_mime_type'] = implode(",", $post_mime_type);
		}

		// Addition of taxonomy: Only if selected 1 element: Yes or No. None, or both elements, do nothing
		$taxonomy = $this->get_taxonomy();
		if ($taxonomy && (count($taxonomy) < 2)) {
			
			$taxonomy_id = GD_CONF_MEDIA_CAT_RESOURCES;
			$term = get_term($taxonomy_id, GD_MLA_MEDIA_TAXONOMY);			
			$args[GD_MLA_MEDIA_TAXONOMY] = $term->slug;

			// If selected 'No', then say Attachment not in that taxonomy
			if ($taxonomy[0] == -1) {

				$args[GD_MLA_MEDIA_TAX_OPERATOR] = 'NOT IN';
			}
		}
		
		return $args;
	}
	
	function get_media_filtercomponents() {
	
		return array();
	}
	
	function get_post_mime_type() {
	
		if (!($filtercomponents = $this->get_media_filtercomponents())) {
			return;
		}

		$post_mime_type = array();
		foreach ($filtercomponents as $filtercomponent) {
		
			$post_mime_type = array_merge($post_mime_type, $filtercomponent->get_post_mime_type());
		}
		
		// Transform the post_mime_type keys to actual values
		$post_mime_type = array_map('gd_get_media_type_value', $post_mime_type);
		
		// Protection against hackers: filter for empty values (no need to reset to 'all', that value is already set in dataloader-list-media.php)
		$post_mime_type = array_filter($post_mime_type);
		
		return $post_mime_type;	
	}

	function get_taxonomy() {
	
		if (!($filtercomponents = $this->get_media_filtercomponents())) {
			return;
		}

		$taxonomy = array();
		foreach ($filtercomponents as $filtercomponent) {

			$taxonomy = array_merge($taxonomy, $filtercomponent->get_taxonomy());
		}
		
		return array_filter($taxonomy);	
	}
}
