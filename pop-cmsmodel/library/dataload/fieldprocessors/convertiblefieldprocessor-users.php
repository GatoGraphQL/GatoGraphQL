<?php
namespace PoP\CMSModel;

define ('GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_USERS', 'convertible-users');

class ConvertibleFieldProcessor_Users extends \PoP\Engine\ConvertibleFieldProcessorBase {

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
new ConvertibleFieldProcessor_Users();
