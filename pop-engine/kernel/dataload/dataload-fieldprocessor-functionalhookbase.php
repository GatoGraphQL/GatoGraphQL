<?php
namespace PoP\Engine;
 
abstract class FieldProcessor_FunctionalHookBase extends FieldProcessor_HookBase {

	function get_field_type() {

		return GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_FUNCTIONAL;
	}
}