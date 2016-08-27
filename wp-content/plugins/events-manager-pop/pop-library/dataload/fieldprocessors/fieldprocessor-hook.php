<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_EM_DataLoad_FieldProcessor_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(
			GD_DATALOAD_FIELDPROCESSOR_POSTS,
			GD_DATALOAD_FIELDPROCESSOR_USERS,
		);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			case 'locations' :
				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_LOCATIONS);

			case 'has-locations' :
				$locations = $fieldprocessor->get_value($post, 'locations');
				return !empty($locations);

			case 'locationsmap-url':
				$locations = $fieldprocessor->get_value($post, 'locations');
				// Decode it, because add_query_arg sends the params encoded and it doesn't look nice
				return urldecode(add_query_arg(GD_TEMPLATE_FORMCOMPONENT_LOCATIONID, $locations, get_permalink(POP_EM_POPPROCESSORS_PAGE_LOCATIONSMAP)));
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_DataLoad_FieldProcessor_Hook();