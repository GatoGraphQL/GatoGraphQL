<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPGenericForms_DataLoad_FieldProcessor_Users_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_USERS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$user = $resultitem;

		switch ($field) {

			case 'contact-url' :
				return add_query_arg('uid', $fieldprocessor->get_id($user), get_permalink(POP_GENERICFORMS_PAGE_CONTACTUSER));
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPGenericForms_DataLoad_FieldProcessor_Users_Hook();