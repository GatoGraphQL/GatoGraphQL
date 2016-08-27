<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Media Library Assistant plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_wp_get_media_item', 'gd_wp_get_media_item_impl', 10, 5);
function gd_wp_get_media_item_impl($input, $id, $name, $value, $aria_required) { 
	
	if ($id == GD_MLA_MEDIA_TAXONOMY) {

		// Using $name as $id_attr in original (wp-admin/includes/media.php function get_media_item)
		$input = gd_wp_get_compat_media_markup_input_impl($input, $id, $name, $name, $value, false, $aria_required);
	}
	
	return $input; 
}
add_filter('gd_wp_get_compat_media_markup_input', 'gd_wp_get_compat_media_markup_input_impl', 10, 7);
function gd_wp_get_compat_media_markup_input_impl($input, $id, $id_attr, $name, $value, $readonly, $aria_required) { 
	
	if ($id == GD_MLA_MEDIA_TAXONOMY) {
	
		// Original input output:
		// <input type="text" class="text" id="attachments-13657-attachment_category" name="attachments[13657][attachment_category]" value="resources">

		$input =  
			"<select class='text' id='$id_attr' name='$name' $readonly $aria_required >".
				"<option value='' ".($value == "" ? "selected" : "").">".__("Do not add", "greendrinks")."</option>".
				"<option value='resources' ".($value == "resources" ? "selected" : "").">".__("Add to Resources", "greendrinks")."</option>".
			"</select>";
	}
	
	return $input; 
}

add_filter('attachment_fields_to_edit', 'gd_mla_change_label', 10, 2);
function gd_mla_change_label($form_fields, $post) { 
	
	if ($form_fields[GD_MLA_MEDIA_TAXONOMY]) {
	
		$form_fields[GD_MLA_MEDIA_TAXONOMY]['label'] = __('Resources', 'poptheme-wassup');
	}
	
	return $form_fields; 
}


/**---------------------------------------------------------------------------------------------------------------
 * Taxonomy
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_resources_media_taxonomy', 'gd_resources_media_taxonomy_impl');
function gd_resources_media_taxonomy_impl($taxonomy) {

	return GD_MLA_MEDIA_TAXONOMY;
}

/**---------------------------------------------------------------------------------------------------------------
 * dataloader-list.php
 * ---------------------------------------------------------------------------------------------------------------*/
// Allow the dataloader-list to also fetch Attachments
add_filter('gd_dataload:post_types', 'gd_mla_return_attachment_posttype');
function gd_mla_return_attachment_posttype($post_types) {

	$post_types[] = 'attachment';
	return $post_types;
}
/**---------------------------------------------------------------------------------------------------------------
 * Integration with All Content
 * ---------------------------------------------------------------------------------------------------------------*/
// Comment Leo: Uncomment here! This works fine, disabled until adding the Media Resources
// add_filter('gd_templatemanager:multilayout_labels', 'gd_mla_custom_multilayout_labels');
// function gd_mla_custom_multilayout_labels($labels) {

// 	$label = '<span class="label label-%s">%s</span>';
// 	return array_merge(
// 		array(
// 			'attachment-'.POPTHEME_WASSUP_MLA_CAT_RESOURCES => sprintf(
// 				$label,
// 				'resources',
// 				gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_RESOURCES, true).__('Resource', 'poptheme-wassup')
// 			)
// 		),
// 		$labels
// 	);
// }

add_filter('gd_template:allcontent:post_types', 'gd_mla_return_attachment_posttype');
add_filter('gd_template:allcontent:tax_query_items', 'gd_mla_template_everything_taxquery');
add_filter('gd_template:latestcounts:tax_query_items', 'gd_mla_template_everything_taxquery');
function gd_mla_template_everything_taxquery($tax_query_items) {
	$tax_query_items[] = array(
		'taxonomy' => gd_resources_media_taxonomy(),
		'terms' => array(POPTHEME_WASSUP_MLA_CAT_RESOURCES)
	);

	return $tax_query_items;
}
