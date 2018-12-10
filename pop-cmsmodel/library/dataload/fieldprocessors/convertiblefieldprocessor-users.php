<?php

define ('GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_USERS', 'convertible-users');

class GD_DataLoad_ConvertibleFieldProcessor_Users extends GD_DataLoad_ConvertibleFieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_USERS;
	}

	protected function get_default_fieldprocessor() {

		return GD_DATALOAD_FIELDPROCESSOR_USERS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ConvertibleFieldProcessor_Users();
