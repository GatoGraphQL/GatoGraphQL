<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * JSON functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Returns true if the response format must be in JSON
function doing_json() {

	return isset($_REQUEST[GD_URLPARAM_OUTPUT]) && $_REQUEST[GD_URLPARAM_OUTPUT] == GD_URLPARAM_OUTPUT_JSON;
}


// Priority: execute last
add_filter( 'template_include', 'gd_template_include_json_impl', 9999999);
add_filter( 'comments_template', 'gd_template_include_json_impl', 9999999);
function gd_template_include_json_impl( $template ){

	if (!doing_json()) {
	
		return $template;
	}

	return POP_ENGINE_KERNEL_TEMPLATES.'/json.php';
}