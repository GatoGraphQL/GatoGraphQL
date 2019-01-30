<?php
namespace PoP\CMSModel;

define ('GD_DATALOAD_FIELDPROCESSOR_TAGS', 'tags');

class FieldProcessor_Tags extends \PoP\Engine\FieldProcessorBase {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_TAGS;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_TAGS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
    	$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$tag = $resultitem;
		switch ($field) {

			case 'url' :
				$value = $cmsapi->get_tag_link($this->get_id($tag));
				break;	

			case 'endpoint' :

				$value = \PoP\Engine\APIUtils::get_endpoint($this->get_value($resultitem, 'url'));
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

		$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
		$tag = $resultitem;
		return $cmsresolver->get_tag_term_id($tag);
	}

	function get_field_default_dataloader($field) {

		// First Check if there's a hook to implement this field
		$default_dataloader = $this->get_hook_field_default_dataloader(GD_DATALOAD_FIELDPROCESSOR_TAGS, $field);
		if ($default_dataloader) {
			return $default_dataloader;
		}

		switch ($field) {

			case 'parent' :
				return GD_DATALOADER_TAGLIST;
		}

		return parent::get_field_default_dataloader($field);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new FieldProcessor_Tags(); 