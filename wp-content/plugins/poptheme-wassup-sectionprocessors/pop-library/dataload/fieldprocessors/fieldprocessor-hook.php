<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_Custom_DataLoad_FieldProcessor_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(
			GD_DATALOAD_FIELDPROCESSOR_POSTS,
		);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			case 'addproject-url':
			case 'addprojectlink-url':
			case 'adddiscussion-url':
			case 'adddiscussionlink-url':
			case 'addstory-url':
			case 'addstorylink-url':
			case 'addannouncement-url':
			case 'addannouncementlink-url':
			case 'addfeatured-url':
			case 'addevent-url':
			case 'addeventlink-url':

				$pages = array(
					'addproject-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECT,
					'addprojectlink-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECTLINK,
					'adddiscussion-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION,
					'adddiscussionlink-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK,
					'addstory-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY,
					'addstorylink-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK,
					'addannouncement-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT,
					'addannouncementlink-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK,
					'addfeatured-url' => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDFEATURED,
					'addevent-url' => POPTHEME_WASSUP_EM_PAGE_ADDEVENT,
					'addeventlink-url' => POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK,
				);
				$page = $pages[$field];
				return add_query_arg(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES.'[]', $fieldprocessor->get_id($post), get_permalink($page));
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_DataLoad_FieldProcessor_Hook();