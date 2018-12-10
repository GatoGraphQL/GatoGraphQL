<?php

define ('GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS', 'convertible-posts');

class GD_DataLoad_ConvertibleFieldProcessor_Posts extends GD_DataLoad_ConvertibleFieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS;
	}

	protected function get_default_fieldprocessor() {

		return GD_DATALOAD_FIELDPROCESSOR_POSTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ConvertibleFieldProcessor_Posts();
