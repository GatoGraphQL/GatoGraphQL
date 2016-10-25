<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class VotingProcessors_DataLoad_FieldProcessor_Posts_Hook extends GD_DataLoad_FieldProcessor_HookBase {

	function get_fieldprocessors_to_hook() {
		
		return array(
			GD_DATALOAD_FIELDPROCESSOR_POSTS,
		);
	}

	function get_value($resultitem, $field, $fieldprocessor) {

		$post = $resultitem;

		switch ($field) {

			case 'stance':
				if (POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO && has_category(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO, $post)) {
					return 'pro';
				}
				elseif (POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST && has_category(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST, $post)) {
					return 'against';
				}
				elseif (POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL && has_category(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL, $post)) {
					return 'neutral';
				}

				return null;

			case 'stance-text':
				$selected = $fieldprocessor->get_value($resultitem, 'stance');
				$params = array(
					'selected' => $selected
				);
				$stance = new GD_FormInput_Stance($params);
				return $stance->get_selected_value();

			case 'content' :

				// Add the quotes around the content for the OpinionatedVoted
				if (POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES && (gd_get_the_main_category($fieldprocessor->get_id($post)) == POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES)) {
					
					// Remove the embed functionality, and then add again
					$wp_embed = $GLOBALS['wp_embed'];
					remove_filter( 'the_content', array( $wp_embed, 'autoembed' ), 8 );
					// $ret = sprintf(
					// 	'<span class="pop-content-quote left"><i class="fa fa-quote-left"></i></span> %s <span class="pop-content-quote right"><i class="fa fa-quote-right"></i></span>',
					// 	// Enabling this, then the show more/less will stop at the </p> so it's still a lot of text to show
					// 	// We also do not want youtube videos embedded. This way is better
					// 	apply_filters('the_content', $post->post_content)
					// 	// make_clickable($post->post_content)
					// );
					$ret = apply_filters('the_content', $post->post_content);
					$ret = apply_filters('pop_content', $ret, $fieldprocessor->get_id($post));
					add_filter( 'the_content', array( $wp_embed, 'autoembed' ), 8 );
					return $ret;
				}
				break;

			case 'addopinionatedvote-url':

				$pages = array(
					'addopinionatedvote-url' => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE,
				);
				$page = $pages[$field];
				return add_query_arg(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES.'[]', $fieldprocessor->get_id($post), get_permalink($page));

			case 'opinionatedvotereferencedby':
				
				// $meta_query = array(
				// 	'key' => GD_MetaManager::get_meta_key(GD_METAKEY_POST_REFERENCES),
				// 	'value' => array($fieldprocessor->get_id($post)),
				// 	'compare' => 'IN'
				// );

				// $query = array(
				// 	'fields' => 'ids',
				// 	'posts_per_page' => -1, // Bring all the results
				// 	'meta_query' => array($meta_query),
				// 	'cat' => POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES,
				// 	'orderby' => 'date',
				// 	'order' => 'ASC',
				// );
				$query = array(
					// 'fields' => 'ids',
					'posts_per_page' => -1, // Bring all the results
					'orderby' => 'date',
					'order' => 'ASC',
				);
				VotingProcessors_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_opinionatedvotereferences($query, $fieldprocessor->get_id($post));

				return get_posts($query);

			case 'has-opinionatedvotereferencedby':

				$referencedby = $fieldprocessor->get_value($resultitem, 'opinionatedvotereferencedby');
				return !empty($referencedby);

			case 'loggedinuser-opinionatedvotereferencedby':
				
				$query = array(
					'author' => get_current_user_id(),
				);
				VotingProcessors_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_opinionatedvotereferences($query, $fieldprocessor->get_id($post));

				return get_posts($query);

			case 'has-loggedinuser-opinionatedvotereferencedby':

				$referencedby = $fieldprocessor->get_value($resultitem, 'loggedinuser-opinionatedvotereferencedby');
				return !empty($referencedby);

			case 'editopinionatedvote-url':

				if ($referencedby = $fieldprocessor->get_value($resultitem, 'loggedinuser-opinionatedvotereferencedby')) {
					return urldecode(get_edit_post_link($referencedby[0]));
				}
				return null;

			case 'postopinionatedvotes-pro-url':
			case 'postopinionatedvotes-neutral-url':
			case 'postopinionatedvotes-against-url':

				$pages = array(
					'postopinionatedvotes-pro-url' => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO,
					'postopinionatedvotes-neutral-url' => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL,
					'postopinionatedvotes-against-url' => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST,
				);
				$url = get_permalink($fieldprocessor->get_id($post));
				return GD_TemplateManager_Utils::add_tab($url, $pages[$field]);

			case 'stance-pro-count':
			case 'stance-neutral-count':
			case 'stance-against-count':

				$cats = array(
					'stance-pro-count' => POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO,
					'stance-neutral-count' => POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL,
					'stance-against-count' => POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST,
				);

				$query = array();
				VotingProcessors_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_opinionatedvotereferences($query, $fieldprocessor->get_id($post));

				// Override the category
				$query['cat'] = $cats[$field];

				// All results
				$query['posts_per_page'] = 0;

				// Taken from https://stackoverflow.com/questions/2504311/wordpress-get-post-count
				$wp_query = new WP_Query();
				$wp_query->query($query);
				$count = $wp_query->found_posts;
				return $count;

			/**------------------------------------
			 * Lazy Loading fields
			 --------------------------------------*/
			case 'createopinionatedvotebutton-lazy':
				return null;

			case 'opinionatedvotereferencedby-lazy|details':
			// case 'opinionatedvotereferencedby-lazy|simpleview':
			case 'opinionatedvotereferencedby-lazy|fullview':
				return array();
		}

		return parent::get_value($resultitem, $field, $fieldprocessor);
	}	
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_DataLoad_FieldProcessor_Posts_Hook();