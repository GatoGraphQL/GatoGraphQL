<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PS_POP_DataLoad_FieldProcessor_Posts_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		return array(GD_DATALOAD_FIELDPROCESSOR_POSTS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			case 'thumb-full-dimensions' :			
				// This is the format needed by PhotoSwipe under attr data-size
				$thumb = $fieldprocessor->get_value($resultitem, 'thumb-full');
				return sprintf(
					'%sx%s',
					$thumb['width'],
					$thumb['height']
				);
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PS_POP_DataLoad_FieldProcessor_Posts_Hook();