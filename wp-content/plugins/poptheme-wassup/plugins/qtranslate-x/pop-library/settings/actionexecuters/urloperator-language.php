<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_QT_Settings_UrlOperator_Language extends GD_Settings_UrlOperator {

	function get_url($url, $field, $value) {

		switch ($field) {

			case GD_QT_TEMPLATE_FORMCOMPONENT_LANGUAGE:

				$url = qtranxf_convertURL($url, $value);

				// Delete the qtrans_front_language cookie so that the plug-in doesn't get confused and redirects to MS again instead of EN (since EN is default, lang not available in URL)
				qtranxf_set_language_cookie($value);

				break;
		}
    
		return $url;
	}
}