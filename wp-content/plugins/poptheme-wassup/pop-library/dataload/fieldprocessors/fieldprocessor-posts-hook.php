<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_Custom_DataLoad_FieldProcessor_Posts_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(
			GD_DATALOAD_FIELDPROCESSOR_POSTS,
		);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;
		$cat = gd_get_the_main_category($fieldprocessor->get_id($post));

		switch ($field) {

			// Using hook so that it is also accessed from the events fieldprocessor (it extends from GD_DATALOAD_FIELDPROCESSOR_POSTS)
			case 'disclaimer':
				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_DISCLAIMERURL, true);

			/**-------------------------------------
			 * Override fields for Links
			 **-------------------------------------*/
			case 'excerpt' :
			case 'content' :

				// if (POPTHEME_WASSUP_CAT_WEBPOSTLINKS && ($cat == POPTHEME_WASSUP_CAT_WEBPOSTLINKS)) {
				if (POPTHEME_WASSUP_CAT_WEBPOSTLINKS && has_category(POPTHEME_WASSUP_CAT_WEBPOSTLINKS, $post)) {
					
					if ($field == 'excerpt') {
						return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_excerpt($post);
					}

					return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_content($post);
				}
				elseif (POPTHEME_WASSUP_CAT_HIGHLIGHTS && ($cat == POPTHEME_WASSUP_CAT_HIGHLIGHTS)) {

					// Remove the embed functionality, and then add again
					$wp_embed = $GLOBALS['wp_embed'];
					remove_filter( 'the_content', array( $wp_embed, 'autoembed' ), 8 );

					// Do not allow HTML tags or shortcodes
					$ret = strip_tags(strip_shortcodes($post->post_content));
					$ret = apply_filters('the_content', $ret);
					$ret = apply_filters('pop_content', $ret, $fieldprocessor->get_id($post));
					add_filter( 'the_content', array( $wp_embed, 'autoembed' ), 8 );
					return $ret;
				}
				break;

			/**-------------------------------------*/

			case 'linkaccess':
				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_LINKACCESS, true);

			case 'linkaccess-string':
				$selected = $fieldprocessor->get_value($post, 'linkaccess');
				$params = array(
					'selected' => $selected
				);
				$linkaccess = new GD_FormInput_LinkAccessDescription($params);
				return $linkaccess->get_selected_value();

			case 'linkcategories':
				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_LINKCATEGORIES);

			case 'linkcategories-strings':				
				$selected = $fieldprocessor->get_value($post, 'linkcategories');
				$params = array(
					'selected' => $selected
				);
				$linkcategories = new GD_FormInput_LinkCategories($params);
				return $linkcategories->get_selected_value();

			case 'categories':

				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_CATEGORIES);

			case 'categories-strings':				
				
				$selected = $fieldprocessor->get_value($post, 'categories');
				$params = array(
					'selected' => $selected
				);
				$categories = new GD_FormInput_Categories($params);
				return $categories->get_selected_value();

			case 'has-categories':				
				
				if ($fieldprocessor->get_value($post, 'categories')) {
					return true;
				}
				return false;

			case 'appliesto':

				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_APPLIESTO);

			case 'appliesto-strings':				
				
				$selected = $fieldprocessor->get_value($post, 'appliesto');
				$params = array(
					'selected' => $selected
				);
				$appliesto = new GD_FormInput_AppliesTo($params);
				return $appliesto->get_selected_value();

			case 'has-appliesto':				
				
				if ($fieldprocessor->get_value($post, 'appliesto')) {
					return true;
				}
				return false;

			case 'has-linkcategories':				
					
				if ($fieldprocessor->get_value($post, 'linkcategories')) {
					return true;
				}
				return false;

			case 'addwebpostlink-url':
			case 'addwebpost-url':
			case 'addhighlight-url':

				$pages = array(
					'addwebpostlink-url' => POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK,
					'addwebpost-url' => POPTHEME_WASSUP_PAGE_ADDWEBPOST,
					'addhighlight-url' => POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT,
				);
				$page = $pages[$field];
				return add_query_arg(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES.'[]', $fieldprocessor->get_id($post), get_permalink($page));

			case 'highlightreferencedby-lazy|details':
			case 'highlightreferencedby-lazy|simpleview':
			case 'highlightreferencedby-lazy|fullview':
				return array();

			case 'highlightreferencedby':
				
				$meta_query = array(
					'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_REFERENCES),
					'value' => array($fieldprocessor->get_id($post)),
					'compare' => 'IN'
				);

				$query = array(
					'fields' => 'ids',
					'posts_per_page' => -1, // Bring all the results
					'meta_query' => array($meta_query),
					'cat' => POPTHEME_WASSUP_CAT_HIGHLIGHTS,
					'orderby' => 'date',
					'order' => 'ASC',
				);

				return get_posts($query);

			case 'has-highlightreferencedby':

				$referencedby = $fieldprocessor->get_value($resultitem, 'highlightreferencedby');
				return !empty($referencedby);

			case 'highlightreferencedby-count':

				$referencedby = $fieldprocessor->get_value($resultitem, 'highlightreferencedby');
				return count($referencedby);

			case 'has-userpostactivity':
				// User Post Activity: Comments + Responses/Additionals + Hightlights
				return 
					$fieldprocessor->get_value($resultitem, 'has-comments') ||
					$fieldprocessor->get_value($resultitem, 'has-referencedby') ||
					$fieldprocessor->get_value($resultitem, 'has-highlightreferencedby');

			case 'userpostactivity-count':
				// User Post Activity: Comments + Responses/Additionals + Hightlights
				return 
					$fieldprocessor->get_value($resultitem, 'comments-count') +
					$fieldprocessor->get_value($resultitem, 'referencedby-count') +
					$fieldprocessor->get_value($resultitem, 'highlightreferencedby-count');
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_DataLoad_FieldProcessor_Posts_Hook();