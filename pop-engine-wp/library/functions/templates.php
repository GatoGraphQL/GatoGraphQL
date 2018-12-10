<?php

// Priority: execute last
add_filter( 'template_include', 'pop_engine_template_include_json', PHP_INT_MAX);
function pop_engine_template_include_json( $template ){

	// If doing JSON, for sure return json.php which only prints the encoded JSON
	if (doing_json()) {
	
		return POP_ENGINE_TEMPLATES.'/json.php';
	}
	// Otherwise, if the theme doesn't implement the template, use the default one
	elseif (!$template) {
	
		return POP_ENGINE_TEMPLATES.'/index.php';
	}

	return $template;
}