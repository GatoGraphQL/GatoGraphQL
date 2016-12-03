<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS', 'custom-posts');

class GD_Custom_DataLoad_FieldProcessor_Posts extends GD_DataLoad_FieldProcessor_Posts {

	function get_name() {
	
		return GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS;
	}
	
	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$post = $resultitem;		

		// Category Specific: needed for compatibility with GD_DataLoader_ConvertiblePostList
		// (So that won't have both locationpost-caterories and discussion-categories)
		$cat = gd_get_the_main_category($this->get_id($post));
		
		switch ($field) {

			/**-------------------------------------
			 * Override fields for Links
			 **-------------------------------------*/
			case 'excerpt' :
			case 'content' :

				if (
					(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS && POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTLINKS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS) && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTLINKS, $post)) ||
					(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS && POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONLINKS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS) && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONLINKS, $post)) ||
					(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS && POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS) && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS, $post)) ||
					(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES && POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORYLINKS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES) && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORYLINKS, $post))
					) {
					
					if ($field == 'excerpt') {
						return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_excerpt($post);
					}

					return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_content($post);
				}
				break;
		
			case 'locationpostcategories':
				if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS)) {
					
					return GD_MetaManager::get_post_meta($this->get_id($post), GD_METAKEY_POST_LOCATIONPOSTCATEGORIES);
				}
				break;

			case 'locationpostcategories-strings':				
				
				if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS)) {
					$selected = $this->get_value($post, 'locationpostcategories');
					$params = array(
						'selected' => $selected
					);
					$locationpostcategories = new GD_FormInput_LocationPostCategories($params);
					return $locationpostcategories->get_selected_value();
				}
				break;

			case 'has-locationpostcategories':				
				
				if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS)) {
					
					if ($this->get_value($post, 'locationpostcategories')) {
						return true;
					}
					return false;
				}
				break;

			case 'discussioncategories':

				if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS)) {
					return GD_MetaManager::get_post_meta($this->get_id($post), GD_METAKEY_POST_DISCUSSIONCATEGORIES);
				}
				break;

			case 'discussioncategories-strings':				
					
				if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS)) {
					$selected = $this->get_value($post, 'discussioncategories');
					$params = array(
						'selected' => $selected
					);
					$discussioncategories = new GD_FormInput_DiscussionCategories($params);
					return $discussioncategories->get_selected_value();
				}
				break;

			case 'has-discussioncategories':				
				
				if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS && ($cat == POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS)) {
					
					if ($this->get_value($post, 'discussioncategories')) {
						return true;
					}
					return false;
				}
				break;
		}

		return parent::get_value($resultitem, $field);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_DataLoad_FieldProcessor_Posts();
