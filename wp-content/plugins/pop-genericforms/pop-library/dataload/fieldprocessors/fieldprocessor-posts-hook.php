<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPGenericForms_DataLoad_FieldProcessor_Posts_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_POSTS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			case 'flag-url' :
				return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_GENERICFORMS_PAGE_FLAG));

			case 'volunteer-url' :
				return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_GENERICFORMS_PAGE_VOLUNTEER));

			case 'volunteers-needed':
				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_VOLUNTEERSNEEDED, true);

			case 'volunteers-needed-string':
				return GD_DataLoad_FieldProcessor_Utils::get_boolvalue_string($fieldprocessor->get_value($resultitem, 'volunteers-needed'));
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPGenericForms_DataLoad_FieldProcessor_Posts_Hook();