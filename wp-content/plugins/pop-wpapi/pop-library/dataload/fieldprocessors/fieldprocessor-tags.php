<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

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
	
		$tag = $resultitem;		
		
		switch ($field) {
			
			// Needed for tinyMCE-mention plug-in
			case 'mention-queryby' :
				$value = $this->get_value($tag, 'name');
				break;

			case 'url' :
				$value = get_tag_link($this->get_id($tag));
				break;	

			case 'symbol' :
				$value = PoP_TagUtils::get_tag_symbol();
				break;

			case 'name' :
				$value = $tag->name;
				break;

			case 'namedescription' :
				$value = PoP_TagUtils::get_tag_namedescription($tag);
				break;

			case 'slug' :
				$value = $tag->slug;
				break;

			case 'term_group' :
				$value = $tag->term_group;
				break;

			case 'term_taxonomy_id' :
				$value = $tag->term_taxonomy_id;
				break;

			case 'taxonomy' :
				$value = $tag->taxonomy;
				break;

			case 'description' :
				$value = $tag->description;
				break;

			case 'parent' :
				$value = $tag->parent;
				break;

			case 'count' :
				$value = $tag->count;
				break;
 
			default:
				$value = parent::get_value($resultitem, $field);
				break;																														
		}

		return $value;
	}	

	function get_id($resultitem) {

		$tag = $resultitem;
		return $tag->term_id;		
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Tags(); 