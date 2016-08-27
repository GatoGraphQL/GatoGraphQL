<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Settings_UrlOperator_Format extends GD_Settings_UrlOperator {

	function get_url($url, $field, $value) {

		switch ($field) {

			case GD_TEMPLATE_FORMCOMPONENT_SETTINGSFORMAT:

				$url = add_query_arg(GD_URLPARAM_FORMAT, $value, $url);
				break;
		}
    
		return $url;
	}
}