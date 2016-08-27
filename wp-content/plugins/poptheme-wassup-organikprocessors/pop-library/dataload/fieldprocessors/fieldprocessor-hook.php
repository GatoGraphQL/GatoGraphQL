<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class OrganikProcessors_DataLoad_FieldProcessor_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(
			GD_DATALOAD_FIELDPROCESSOR_POSTS,
		);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			/**-------------------------------------
			 * Override fields for Links
			 **-------------------------------------*/
			case 'excerpt' :
			case 'content' :

				if (
					(POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS && POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMLINKS && ($cat == POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS) && has_category(POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMLINKS, $post))
					) {
					
					if ($field == 'excerpt') {
						return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_excerpt($post);
					}

					return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_content($post);
				}
				break;
		
			case 'farmcategories':
				if (POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS && ($cat == POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS)) {
					
					return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_FARMCATEGORIES);
				}
				break;

			case 'farmcategories-strings':				
				
				if (POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS && ($cat == POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS)) {
					$selected = $fieldprocessor->get_value($post, 'farmcategories');
					$params = array(
						'selected' => $selected
					);
					$farmcategories = new GD_FormInput_FarmCategories($params);
					return $farmcategories->get_selected_value();
				}
				break;

			case 'has-farmcategories':				
				
				if (POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS && ($cat == POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS)) {
					
					if ($fieldprocessor->get_value($post, 'farmcategories')) {
						return true;
					}
					return false;
				}
				break;

			case 'addfarm-url':
			case 'addfarmlink-url':

				$pages = array(
					'addfarm-url' => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM,
					'addfarmlink-url' => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK,
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
new OrganikProcessors_DataLoad_FieldProcessor_Hook();