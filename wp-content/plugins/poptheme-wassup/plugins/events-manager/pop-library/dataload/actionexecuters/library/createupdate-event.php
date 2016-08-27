<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_CreateUpdate_Event extends GD_CustomCreateUpdate_Post {

	protected function volunteer() {

		return true;
	}

	// Update Post Validation
	protected function validatecontent(&$errors, $form_data) {

		parent::validatecontent($errors, $form_data);

		// Validate also for 'draft' status, otherwise EM doesn't create the Event
		if (empty($form_data['when'])) {
			$errors[] = __('The dates/time cannot be empty', 'poptheme-wassup');
		}
	}

	protected function get_form_data($atts) {

		$form_data = parent::get_form_data($atts);

		global $gd_template_processor_manager;

		$locations = $gd_template_processor_manager->get_processor(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP)->get_value(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts);
		// Location must be unique
		if ($locations) {
			$location = $locations[0];
		}

		$form_data['location'] = $location;
		$form_data['when'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER)->get_value(GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER, $atts);
		
		return $form_data;
	}

	protected function get_createpost_data($form_data) {

		$post_data = parent::get_createpost_data($form_data);
		$post_data = $this->get_createupdatepost_data($post_data, $form_data);

		return $post_data;
	}

	protected function get_updatepost_data($form_data) {

		$post_data = parent::get_updatepost_data($form_data);
		$post_data = $this->get_createupdatepost_data($post_data, $form_data);

		return $post_data;
	}

	protected function get_createupdatepost_data($post_data, $form_data) {

		// Unset the cat, not needed with events
		unset($post_data['post_category']);

		$post_data['when'] = $form_data['when'];
		$post_data['location'] = $form_data['location'];

		return $post_data;
	}

	protected function populate(&$EM_Event, $post_data) {

		// Copied from function get_post($validate = true) in events-manager/classes/em-event.php
		$EM_Event->post_content = $post_data['post_content'];
		$EM_Event->event_name = $post_data['post_title'];
		$EM_Event->post_type = EM_POST_TYPE_EVENT;

		// Comment Leo 13/03/2016: this line is MANDATORY! When it is not there, the post_except will be set as NULL,
		// and it fails to create the event, giving error "Column 'post_excerpt' cannot be null" on wp_insert_post()
		$EM_Event->post_excerpt = '';

		// post_status might be empty (for publish)
		if ($status = $post_data['post_status']) {
			$EM_Event->force_status = $status;
		}
		
		// Copied from function get_post_meta($validate = true) in events-manager/classes/em-event.php
		// Start/End date and time
		$EM_Event->event_start_date = $post_data['when']['from'];
		$EM_Event->event_end_date = $post_data['when']['to'];

		// Location
		if ($post_data['location']) {

			$EM_Location = em_get_location($post_data['location'], 'post_id');
			$EM_Event->location_id = $EM_Location->location_id;	
		}
		// No location
		else {
			$EM_Event->location_id = 0;
		}
		
		// TODO: Fix this: the "All Day" status should be selected in the Bootstrap daterange picker
		// Right now horrible fix: if fromtime and totime are both '00:00' then it's all day
		$EM_Event->event_all_day = ($post_data['when']['fromtime'] == '00:00' && $post_data['when']['totime'] == '00:00') ? 1 : 0;
		$EM_Event->event_start_time = $post_data['when']['fromtime'] . ':00';
		$EM_Event->event_end_time = $post_data['when']['totime'] . ':00';

		//Start/End times should be available as timestamp
		$EM_Event->start = strtotime($EM_Event->event_start_date." ".$EM_Event->event_start_time);
		$EM_Event->end = strtotime($EM_Event->event_end_date." ".$EM_Event->event_end_time);
		
		//Set Blog ID
		if( is_multisite() ){
			$EM_Event->blog_id = get_current_blog_id();
		}

		//group id
		$EM_Event->group_id = 0;
		
		return $EM_Event;
	}

	protected function save(&$EM_Event, $post_data) {

		$EM_Event = $this->populate($EM_Event, $post_data);
		$EM_Event->save();
		return $EM_Event->post_id;
	}

	protected function execute_updatepost($post_data) {

		$EM_Event = new EM_Event($post_data['ID'], 'post_id');
		return $this->save($EM_Event, $post_data);
	}

	protected function execute_createpost($post_data) {

		$EM_Event = new EM_Event();
		return $this->save($EM_Event, $post_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// global $gd_createupdate_event;
// $gd_createupdate_event = new GD_CreateUpdate_Event();