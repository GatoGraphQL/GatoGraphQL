<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_FieldProcessor_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(
			GD_DATALOAD_FIELDPROCESSOR_POSTS,
			GD_DATALOAD_FIELDPROCESSOR_USERS,
			GD_DATALOAD_FIELDPROCESSOR_TAGS,
		);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		switch ($field) {

			case 'print-url':

				$url = $fieldprocessor->get_value($resultitem, 'url');
				return GD_TemplateManager_Utils::get_print_url($url);
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Hook();