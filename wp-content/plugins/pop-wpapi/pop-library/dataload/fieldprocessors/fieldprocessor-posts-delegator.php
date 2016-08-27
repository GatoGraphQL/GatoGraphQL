<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR', 'posts-delegator');

class GD_DataLoad_FieldProcessor_Posts_Delegator extends GD_DataLoad_FieldProcessor_Posts {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR;
	}

	/**
	 * Array of:
	 * key: post_type
	 * value: fieldprocessor name
	 */
	function get_fieldprocessors() {

		return apply_filters('gd_dataload:'.$this->get_name().':fieldprocessors', array());
	}

	protected function get_fieldprocessor($post_type) {

		global $gd_dataload_fieldprocessor_manager;

		$fieldprocessors = $this->get_fieldprocessors();
		if (!$fieldprocessor_name = $fieldprocessors[$post_type]) {
			throw new Exception(sprintf('No Fieldprocessor for post type \'%s\'', $post_type));
		}

		return $gd_dataload_fieldprocessor_manager->get($fieldprocessor_name);
	}
	
	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$post = $resultitem;
		$post_type = get_post_type($post);

		// Delegate to the Fieldprocessor corresponding to this post type
		$fieldprocessor = $this->get_fieldprocessor($post_type);

		// Cast object, from 'post' to the corresponding post_type
		$converted = $fieldprocessor->cast($post);

		return $fieldprocessor->get_value($converted, $field);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Posts_Delegator();
