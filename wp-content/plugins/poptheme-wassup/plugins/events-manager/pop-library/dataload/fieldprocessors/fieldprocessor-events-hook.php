<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_EM_DataLoad_FieldProcessor_Events_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(
			// GD_DATALOAD_FIELDPROCESSOR_POSTS,
			GD_DATALOAD_FIELDPROCESSOR_EVENTS,
		);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$event = $resultitem;

		switch ($field) {

			/**-------------------------------------
			 * Override fields for Links
			 **-------------------------------------*/
			case 'excerpt' :
			case 'content' :

				if (POPTHEME_WASSUP_EM_CAT_EVENTLINKS && gd_em_has_category($event, POPTHEME_WASSUP_EM_CAT_EVENTLINKS)) {

					if ($field == 'excerpt') {
						return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_excerpt($event);
					}

					return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_content($event);
				}
				break;
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_DataLoad_FieldProcessor_Events_Hook();