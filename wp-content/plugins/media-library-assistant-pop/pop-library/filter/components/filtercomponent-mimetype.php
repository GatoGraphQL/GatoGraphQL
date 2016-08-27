<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_MimeType extends GD_FilterComponent_Media {
	
	function get_post_mime_type() {

		return $this->get_filterformcomponent_value();
	}
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_MIMETYPE;
	}
	
	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MIMETYPE;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_mimetype;
$gd_filtercomponent_mimetype = new GD_FilterComponent_MimeType();