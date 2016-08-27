<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_PPP_DataLoad_FieldProcessor_Profiles_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_POSTS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			case 'coauthors' :

				$authors = $fieldprocessor->get_value($resultitem, 'authors');

				// This function only makes sense when the user is logged in
				if (is_user_logged_in()) {
					
					array_splice($authors, array_search(get_current_user_id(), $authors), 1);
				}
				return $authors;

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
new GD_PPP_DataLoad_FieldProcessor_Profiles_Hook();