<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PPPPoP_DataLoad_FieldProcessor_Profiles_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_POSTS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			case 'preview-url':
				// Use function get_id to also cater for events (whose ID is $event->post_id)
				return gd_ppp_preview_link($fieldprocessor->get_id($post));
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PPPPoP_DataLoad_FieldProcessor_Profiles_Hook();