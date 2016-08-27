<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_Post {

	protected function get_categories($form_data) {

		return null;
	}

	protected function is_featuredimage_mandatory() {

		return false;
	}

	protected function add_references() {

		return true;
	}

	protected function get_editor_input() {

		return GD_TEMPLATE_FORMCOMPONENT_EDITOR;
	}

	protected function get_featuredimage_template() {

		return GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGE;
	}

	protected function moderate() {

		return GD_CreateUpdate_Utils::moderate();
	}

	// Update Post Validation
	protected function validatecontent(&$errors, $form_data) {

		if (empty($form_data['title'])) {
			$errors[] = __('The title cannot be empty', 'pop-wpapi');
		}

		// Validate the following conditions only if status = pending/publish
		if ($form_data['status'] == 'draft') {
			return;
		}

		if (empty($form_data['content'])) {
			$errors[] = __('The content cannot be empty', 'pop-wpapi');
		}

		if ($this->is_featuredimage_mandatory() && empty($form_data['featuredimage'])) {
			$errors[] = __('The featured image has not been set', 'pop-wpapi');
		}
	}

	protected function validatecreatecontent(&$errors, $form_data) {
	}
	protected function validateupdatecontent(&$errors, $form_data) {
	}

	// Update Post Validation
	protected function validatecreate(&$errors) {

		// Validate user permission
		global $userdata;
		$current_user_can = current_user_can( 'edit_posts' );
		if ( !$current_user_can ) {
			$errors[] = __('Your user doesn\'t have permission for editing.', 'pop-wpapi');
		}
	}

	// Update Post Validation
	protected function validateupdate(&$errors) {

		global $gd_template_processor_manager;
		// Comment Leo 03/04/2015: even though the GD_TEMPLATE_FORMCOMPONENT_POSTID field is not part of the Update form, it will still work since the 
		// value with same name is passed through params
		// $post_id = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_POSTID)->get_value(GD_TEMPLATE_FORMCOMPONENT_POSTID);
		$post_id = $_REQUEST['pid'];

		// Validate there is postid
		if (!$post_id) {
			$errors[] = __('Cheating, huh?', 'pop-wpapi');
			return;
		}

		$post = get_post($post_id);
		if (!$post) {
			$errors[] = __('Cheating, huh?', 'pop-wpapi');
			return;
		}

		if (!in_array($post->post_status, array('draft', 'pending', 'publish'))) {
			$errors[] = __('Hmmmmm, this post seems to have been deleted...', 'pop-wpapi');
			return;
		}

		// Validate user permission
		if (!gd_current_user_can_edit($post_id)) {
			$errors[] = __('Your user doesn\'t have permission for editing.', 'pop-wpapi');
		}

		// Validate nonce
		// Comment Leo 04/04/2014: getting value from $_REQUEST since not using the form field anymore, now passing values through params
		// $nonce = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_NONCE)->get_value(GD_TEMPLATE_FORMCOMPONENT_NONCE);
		$nonce = $_REQUEST['_wpnonce'];
		if (!gd_verify_nonce( $nonce, GD_NONCE_EDITURL, $post_id)) {
			$errors[] = __('Incorrect URL', 'pop-wpapi');
			return;
		}
	}

	/**
	 * Function to override
	 */
	protected function additionals($post_id, $form_data) {
	}
	/**
	 * Function to override
	 */
	protected function updateadditionals($post_id, $form_data) {
	}
	/**
	 * Function to override
	 */
	protected function createadditionals($post_id, $form_data) {
	}

	protected function get_form_data($atts) {

		global $gd_template_processor_manager, $allowedposttags;

		$editor = $this->get_editor_input();
		$form_data = array(
			// 'post_id' => $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_POSTID)->get_value(GD_TEMPLATE_FORMCOMPONENT_POSTID),
			'post_id' => $_REQUEST['pid'],
			'title' => trim(strip_tags($gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE, $atts))),
			'content' => trim(wp_kses( stripslashes($gd_template_processor_manager->get_processor($editor)->get_value($editor, $atts)), $allowedposttags)),
		);

		if ($featuredimage = $this->get_featuredimage_template()) {
			$form_data['featuredimage'] = $gd_template_processor_manager->get_processor($featuredimage)->get_value($featuredimage, $atts);
		}

		// Status: 2 possibilities:
		// - Moderate: then using the Draft/Pending/Publish Select, user cannot choose 'Publish' when creating a post
		// - No moderation: using the 'Keep as Draft' checkbox, completely omitting value 'Pending', post is either 'draft' or 'publish'
		if ($this->moderate()) {

			$form_data['status'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS, $atts);
		}
		else {

			$keepasdraft = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CUP_KEEPASDRAFT)->get_value(GD_TEMPLATE_FORMCOMPONENT_CUP_KEEPASDRAFT, $atts);
			$form_data['status'] = $keepasdraft ? 'draft' : 'publish';
		}

		if ($this->add_references()) {
			$form_data['references'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES)->get_value(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES, $atts);
		}
		
		return $form_data;
	}

	protected function get_updatepost_data($form_data) {

		$cats = $this->get_categories($form_data);
		$post_data = array(
			'ID' => $form_data['post_id'],
			'post_title' => $form_data['title'],
			'post_content' => $form_data['content'],
			'post_category' => $cats
		);

		// Status: Validate the value is permitted, or get the default value otherwise
		if ($status = GD_CreateUpdate_Utils::get_updatepost_status($form_data['status'], $this->moderate())) {
			$post_data['post_status'] = $status;
		}

		return $post_data;
	}

	protected function get_createpost_data($form_data) {

		$cats = $this->get_categories($form_data);

		// Status: Validate the value is permitted, or get the default value otherwise
		$status = GD_CreateUpdate_Utils::get_createpost_status($form_data['status'], $this->moderate());
		$post_data = array(
			'post_title' => $form_data['title'],
			'post_content' => $form_data['content'],
			'post_status' => $status,
			'post_category' => $cats
		);

		return $post_data;
	}

	protected function execute_updatepost($post_data) {

		return wp_update_post($post_data);
	}

	protected function createupdatepost(&$errors, $form_data, $post_id) {

		$this->setfeaturedimage($errors, $post_id, $form_data);
		$this->save_references($errors, $post_id, $form_data);
	}

	protected function updatepost(&$errors, $form_data, $atts) {

		$post_data = $this->get_updatepost_data($form_data);
		$post_id = $post_data['ID'];
		
		// Create the operation log, to see what changed. Needed for 
		// - Send email only when post published
		// - Add user notification of post being referenced, only when the reference is new (otherwise it will add the notification each time the user updates the post)
		$log = array(
			'previous-status' => get_post_status($post_id),
		);
		if ($this->add_references()) {
			$previous_references = GD_MetaManager::get_post_meta($post_id, GD_METAKEY_POST_REFERENCES);
			$log['new-references'] = array_diff($form_data['references'], $previous_references);
		}

		$result = $this->execute_updatepost($post_data);

		if ($result === 0) {
			$errors[] = __('Ops, there was a problem... this is embarrassing, huh?', 'pop-wpapi');
			return;
		}

		$this->createupdatepost($errors, $form_data, $post_id);

		// Allow for additional operations (eg: set Action categories)
		$this->additionals($post_id, $form_data);
		$this->updateadditionals($post_id, $form_data);

		// Inject Share profiles here
		do_action('gd_createupdate_post', $post_id, $atts);
		do_action('gd_createupdate_post:update', $post_id, $atts, $log);
	}

	protected function execute_createpost($post_data) {

		return wp_insert_post($post_data);
	}

	protected function createpost(&$errors, $form_data, $atts) {

		$post_data = $this->get_createpost_data($form_data);
		$post_id = $this->execute_createpost($post_data);

		if ($post_id == 0) {

			$errors[] = __('Ops, there was a problem... this is embarrassing, huh?', 'pop-wpapi');
			return;
		}
		
		$this->createupdatepost($errors, $form_data, $post_id);

		// Allow for additional operations (eg: set Action categories)
		$this->additionals($post_id, $form_data);
		$this->createadditionals($post_id, $form_data);

		// Inject Share profiles here
		do_action('gd_createupdate_post', $post_id, $atts);
		do_action('gd_createupdate_post:create', $post_id, $atts);

		return $post_id;
	}

	protected function setfeaturedimage(&$errors, $post_id, $form_data) {

		global $gd_template_processor_manager;

		if ($this->get_featuredimage_template()) {
			
			$featuredimage = $form_data['featuredimage'];
			
			// Featured Image
			if ($featuredimage) {
				set_post_thumbnail($post_id, $featuredimage);
			}
			else {
				delete_post_thumbnail($post_id);
			}
		}
	}

	protected function save_references(&$errors, $post_id, $form_data) {

		if ($this->add_references()) {
			GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_REFERENCES, $form_data['references']);
		}
	}

	public function create_or_update(&$errors, $atts) {

		// If there's post_id => It's Update
		// Otherwise => It's Create
		global $gd_template_processor_manager;
		// $post_id = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_POSTID)->get_value(GD_TEMPLATE_FORMCOMPONENT_POSTID);
		$post_id = $_REQUEST['pid'];

		if ($post_id) {

			$this->update($errors, $atts);
		}
		else {

			$post_id = $this->create($errors, $atts);
		}

		return $post_id;
	}

	protected function update(&$errors, $atts) {

		// If already exists any of these errors above, return errors
		$this->validateupdate($errors);
		if ($errors) {
			return;
		}

		$form_data = $this->get_form_data($atts);

		$this->validateupdatecontent($errors, $form_data);
		$this->validatecontent($errors, $form_data);
		if ($errors) {
			return;
		}

		// Do the Post update
		$this->updatepost($errors, $form_data, $atts);
		// if ($errors) {
		// 	return;
		// }

		// No errors, return empty array (signifying no errors);
		// return array();
	}

	protected function create(&$errors, $atts) {

		// If already exists any of these errors above, return errors
		$this->validatecreate($errors);
		if ($errors) {
			return;
		}

		$form_data = $this->get_form_data($atts);

		$this->validatecreatecontent($errors, $form_data);
		$this->validatecontent($errors, $form_data);
		if ($errors) {
			return;
		}

		// Do the Post update
		$post_id = $this->createpost($errors, $form_data, $atts);
		return $post_id;
		// if ($errors) {
		// 	return;
		// }

		// // No errors, return empty array (signifying no errors);
		// return array();
	}
}
