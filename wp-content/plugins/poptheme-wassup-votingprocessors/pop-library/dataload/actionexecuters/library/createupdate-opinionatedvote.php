<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_OpinionatedVoted extends GD_CreateUpdate_UniqueReferenceBase {

	protected function get_categories($form_data) {

		$stance = $form_data['stance'];
		switch ($stance) {
			
			case 'pro':
				$stance_cat = POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO;
				break;

			case 'against':
				$stance_cat = POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST;
				break;

			case 'neutral':
				$stance_cat = POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL;
				break;
		}

		return array(
			POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES,
			$stance_cat,
		);
	}

	protected function moderate() {

		return false;
	}

	protected function get_success_title($referenced = null) {

		if ($referenced) {

			return sprintf(
				__('%1$s after reading “%2$s”', 'poptheme-wassup-votingprocessors'),
				gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES),//__('Thought on TPP', 'poptheme-wassup-votingprocessors')
				$referenced->post_title
			);
		}

		return gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES);
	}

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;

		$form_data['stance'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE)->get_value(GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE, $atts);
		
		return $form_data;
	}

	protected function validatecreatecontent(&$errors, $form_data) {

		parent::validatecreatecontent($errors, $form_data);

		// For the OpinionatedVoted, there can be at most 1 post for:
		// - Each article: each referenced $post_id
		// - General Thought: only one without a $post_id, set through the homepage
		// If this validation already fails, the rest does not matter
		// Validate that the referenced post has been added (protection against hacking)
		// For highlights, we only add 1 reference, and not more.
		$referenced_id = '';
		if (count($form_data['references']) == 1) {				
			
			$referenced_id = $form_data['references'][0];		
		}
		// Check if there is already an existing opinionatedvote
		$query = array(
			// 'fields' => 'ids',
			'post_status' => array('publish', 'draft'),
			'author' => get_current_user_id(),
		);
		VotingProcessors_Template_Processor_CustomSectionBlocksUtils::add_dataloadqueryargs_opinionatedvotereferences($query, $referenced_id);

		// OpinionatedVoteds are unique, just 1 per person/article. 
		// Check if there is a OpinionatedVoted for the given post. If there is, it's an error, can't create a second OpinionatedVoted.
		if ($opinionatedvotes = get_posts($query)) {

			$opinionatedvote_id = $opinionatedvotes[0];
			$error = sprintf(
				__('You have already added your %s', 'poptheme-wassup-votingprocessors'),
				gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES)//__('Thought on TPP', 'poptheme-wassup-votingprocessors')
			);
			if ($referenced_id) {
				$error = sprintf(
					__('%s after reading “<a href="%s">%s</a>”', 'poptheme-wassup-votingprocessors'),
					$error,
					get_permalink($referenced_id),
					get_the_title($referenced_id)
				);
			}
			$errors[] = sprintf(
				__('%s. <a href="%s" target="%s">Edit?</a>', 'poptheme-wassup-votingprocessors'),
				$error,
				urldecode(get_edit_post_link($opinionatedvote_id)),
				GD_URLPARAM_TARGET_ADDONS
			);
		}
	}

	protected function get_createpost_data($form_data) {

		$post_data = parent::get_createpost_data($form_data);
		
		// Allow to order the Author Thoughts Carousel, so that it always shows the General thought first, and the then article-related ones
		// For that, General thoughts have menu_order "0" (already default one), article-related ones have menu_order "1"
		if (count($form_data['references']) == 1) {	
			$post_data['menu_order'] = 1;
		}

		return $post_data;
	}

	// Update Post Validation
	protected function validatecontent(&$errors, $form_data) {

		parent::validatecontent($errors, $form_data);

		$stances = array('pro', 'against', 'neutral');
		if (empty($form_data['stance'])) {
			$errors[] = __('Please choose your stance', 'poptheme-wassup-votingprocessors');
		}
		elseif (!in_array($form_data['stance'], $stances)) {
			$errors[] = __('Cheating, huh?', 'poptheme-wassup-votingprocessors');
		}
	}

	protected function createadditionals($post_id, $form_data) {

		global $gd_template_processor_manager;

		parent::createadditionals($post_id, $form_data);

		// Allow for URE to add the AuthorRole meta value
		do_action('GD_CreateUpdate_OpinionatedVoted:createadditionals', $post_id, $form_data);
	}
}