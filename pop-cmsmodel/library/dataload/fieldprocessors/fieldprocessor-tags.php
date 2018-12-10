<?php

define ('GD_DATALOAD_FIELDPROCESSOR_TAGS', 'tags');

class GD_DataLoad_FieldProcessor_Tags extends GD_DataLoad_FieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_TAGS;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_TAGS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$cmsresolver = PoP_CMS_ObjectPropertyResolver_Factory::get_instance();
    	$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
		$tag = $resultitem;
		switch ($field) {

			case 'url' :
				$value = $cmsapi->get_tag_link($this->get_id($tag));
				break;	

			case 'name' :
				$value = $cmsresolver->get_tag_name($tag);
				break;

			case 'slug' :
				$value = $cmsresolver->get_tag_slug($tag);
				break;

			case 'term_group' :
				$value = $cmsresolver->get_tag_term_group($tag);
				break;

			case 'term_taxonomy_id' :
				$value = $cmsresolver->get_tag_term_taxonomy_id($tag);
				break;

			case 'taxonomy' :
				$value = $cmsresolver->get_tag_taxonomy($tag);
				break;

			case 'description' :
				$value = $cmsresolver->get_tag_description($tag);
				break;

			case 'parent' :
				$value = $cmsresolver->get_tag_parent($tag);
				break;

			case 'count' :
				$value = $cmsresolver->get_tag_count($tag);
				break;
 
			default:
				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}	

	function get_id($resultitem) {

		$cmsresolver = PoP_CMS_ObjectPropertyResolver_Factory::get_instance();
		$tag = $resultitem;
		return $cmsresolver->get_tag_term_id($tag);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Tags(); 