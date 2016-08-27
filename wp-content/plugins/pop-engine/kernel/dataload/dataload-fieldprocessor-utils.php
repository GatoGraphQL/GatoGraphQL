<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_FieldProcessor_Utils {

	// This is a horrible fix, but needed, because the value true is represented as "1", but we can't save value 
	// "1" in a select because of php arrays (the "1" is interpreted as the array position, and not as the key)
	public static function get_boolvalue_string($value) {

		if ($value) {
			return 'true';
		}
		return 'false';
	}
}
	
