<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_FieldProcessor_Tags_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_TAGS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$tag = $resultitem;

		switch ($field) {

			case 'subscribetotag-url':
				return add_query_arg('tid', $fieldprocessor->get_id($tag), get_permalink(POP_COREPROCESSORS_PAGE_SUBSCRIBETOTAG));

			case 'unsubscribefromtag-url':
				return add_query_arg('tid', $fieldprocessor->get_id($tag), get_permalink(POP_COREPROCESSORS_PAGE_UNSUBSCRIBEFROMTAG));
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Tags_Hook();