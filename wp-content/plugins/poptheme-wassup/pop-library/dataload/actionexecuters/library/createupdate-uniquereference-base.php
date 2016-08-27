<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_UniqueReferenceBase extends GD_CustomCreateUpdate_Post {

	protected function moderate() {

		return false;
	}

	protected function get_editor_input() {

		return GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR;
	}

	protected function get_success_title($referenced = null) {

		if ($referenced) {
			return $referenced->post_title;
		}

		return null;
	}

	protected function reference_mandatory() {

		return false;
	}

	// Update Post Validation
	protected function validatecontent(&$errors, $form_data) {

		// Validate that the referenced post has been added (protection against hacking)
		// For highlights, we only add 1 reference, and not more.
		if (!$form_data['references']) {				
			
			if ($this->reference_mandatory()) {
				$errors[] = __('No post has been referenced', 'poptheme-wassup');
			}
		}
		elseif (count($form_data['references']) > 1) {

			$errors[] = __('Only one post can be referenced', 'poptheme-wassup');
		}
		else {

			$referenced_id = $form_data['references'][0];		
			
			// Highlights have no title input by the user. Instead, produce the title from the referenced post
			$referenced = get_post($referenced_id);
			if (!$referenced) {

				$errors[] = __('The referenced post does not exist', 'poptheme-wassup');
			}
			else {

				// If the referenced post has not been published yet, then error
				if ($referenced->post_status != 'publish') {

					$errors[] = __('The referenced post is not published yet', 'poptheme-wassup');
				}
			}
		}

		// If cheating then that's it, no need to validate anymore
		if (!$errors) {
			parent::validatecontent($errors, $form_data);
		}
	}

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		if (count($form_data['references']) == 1) {				

			// Highlights have no title input by the user. Instead, produce the title from the referenced post
			$referenced = get_post($form_data['references'][0]);
		}
		$form_data['title'] = $this->get_success_title($referenced);

		return $form_data;
	}
}