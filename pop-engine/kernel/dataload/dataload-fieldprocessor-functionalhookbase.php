<?php
 
define ('GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_FUNCTIONAL', 'functional');

class GD_DataLoad_FieldProcessor_FunctionalHookBase extends GD_DataLoad_FieldProcessor_HookBase {

	function get_field_type() {

		return GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_FUNCTIONAL;
	}
}