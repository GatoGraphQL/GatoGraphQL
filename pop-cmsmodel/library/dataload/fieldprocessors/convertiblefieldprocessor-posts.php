<?php
namespace PoP\CMSModel;

define ('GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS', 'convertible-posts');

class ConvertibleFieldProcessor_Posts extends \PoP\Engine\ConvertibleFieldProcessorBase {

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
new ConvertibleFieldProcessor_Posts();
