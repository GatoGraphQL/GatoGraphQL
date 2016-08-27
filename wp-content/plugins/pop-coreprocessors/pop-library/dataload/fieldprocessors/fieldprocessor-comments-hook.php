<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_FieldProcessor_Comments_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_COMMENTS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$comment = $resultitem;

		switch ($field) {
			
			// Users mentioned in the comment: @mentions
			case 'taggedusers' :
				return GD_MetaManager::get_comment_meta($fieldprocessor->get_id($comment), GD_METAKEY_COMMENT_TAGGEDUSERS);
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Comments_Hook();