<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_FieldProcessor_Posts_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(GD_DATALOAD_FIELDPROCESSOR_POSTS);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			case 'linkcontent' :

				return GD_DataLoad_FieldProcessor_Posts_Utils::get_link_content($post, true);
			
			case 'authors' :
				return gd_get_postauthors($fieldprocessor->get_id($post));

			case 'coauthors' :

				$authors = $fieldprocessor->get_value($resultitem, 'authors');

				// This function only makes sense when the user is logged in
				$vars = GD_TemplateManager_Utils::get_vars();
				if ($vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {
					
					$pos = array_search($vars['global-state']['current-user-id']/*get_current_user_id()*/, $authors);
					if ($pos >= 0) {
						array_splice($authors, $pos, 1);
					}
				}
				return $authors;

			// Users mentioned in the post: @mentions
			case 'taggedusers' :
				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_TAGGEDUSERS);

			case 'favicon' :			
			case 'thumb-xs' :			
			case 'thumb-sm' :			
			case 'thumb-md' :			
			case 'thumb-feed' :			
			case 'thumb-pagewide' :					
				return $fieldprocessor->get_thumb($post, $field);

			// Override
			case 'featuredimage-imgsrc':				
				if ($featuredimage = get_post_thumbnail_id($fieldprocessor->get_id($post))) {
					return $fieldprocessor->get_image_src($featuredimage, 'thumb-md');
				}
				break;

			case 'recommendpost-url':
				return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_COREPROCESSORS_PAGE_RECOMMENDPOST));

			case 'unrecommendpost-url':
				return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST));

			case 'recommendpost-count':
				return (int) GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_RECOMMENDCOUNT, true);

			case 'recommendpost-count-plus1':
				if ($count = $fieldprocessor->get_value($resultitem, 'recommendpost-count')) {
					return $count+1;
				}
				return 1;

			case 'upvotepost-url':
				return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_COREPROCESSORS_PAGE_UPVOTEPOST));

			case 'undoupvotepost-url':
				return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_COREPROCESSORS_PAGE_UNDOUPVOTEPOST));

			case 'upvotepost-count':
				return (int) GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_UPVOTECOUNT, true);

			case 'upvotepost-count-plus1':
				if ($count = $fieldprocessor->get_value($resultitem, 'upvotepost-count')) {
					return $count+1;
				}
				return 1;

			case 'downvotepost-url':
				return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_COREPROCESSORS_PAGE_DOWNVOTEPOST));

			case 'undodownvotepost-url':
				return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_COREPROCESSORS_PAGE_UNDODOWNVOTEPOST));

			case 'downvotepost-count':
				return (int) GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_DOWNVOTECOUNT, true);

			case 'downvotepost-count-plus1':
				if ($count = $fieldprocessor->get_value($resultitem, 'downvotepost-count')) {
					return $count+1;
				}
				return 1;

			// case 'flag-url' :
			// 	return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_GENERICFORMS_PAGE_FLAG));

			// case 'volunteer-url' :
			// 	return add_query_arg('pid', $fieldprocessor->get_id($post), get_permalink(POP_GENERICFORMS_PAGE_VOLUNTEER));

			// case 'volunteers-needed':
			// 	return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_VOLUNTEERSNEEDED, true);

			// case 'volunteers-needed-string':
			// 	return GD_DataLoad_FieldProcessor_Utils::get_boolvalue_string($fieldprocessor->get_value($resultitem, 'volunteers-needed'));

			case 'references':
				return GD_MetaManager::get_post_meta($fieldprocessor->get_id($post), GD_METAKEY_POST_REFERENCES);

			case 'has-references':
				$references = $fieldprocessor->get_value($resultitem, 'references');
				return !empty($references);

			case 'referencedby-lazy|details':
			case 'referencedby-lazy|simpleview':
			case 'referencedby-lazy|fullview':
				return array();

			case 'referencedby':
				
				return PoPCore_Template_Processor_SectionBlocksUtils::get_referencedby($fieldprocessor->get_id($post));

			case 'has-referencedby':

				$referencedby = $fieldprocessor->get_value($resultitem, 'referencedby');
				return !empty($referencedby);

			case 'referencedby-count':

				$referencedby = $fieldprocessor->get_value($resultitem, 'referencedby');
				return count($referencedby);
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Posts_Hook();