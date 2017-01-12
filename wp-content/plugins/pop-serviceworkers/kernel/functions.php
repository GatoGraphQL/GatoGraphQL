<?php

// The ETag header is needed to compare the versions of the JSON code cached by the Service Worker, and
// if it became stale, then refresh it
add_filter('PoP_Engine:output_json:add_etag_header', 'pop_sw_add_etag_header');
function pop_sw_add_etag_header($bool) {

	// If the page does not require user state, then it can be cached using Service Workers,
	// so only then add the ETag header
	// If it does require user state, then we're never caching that page, and adding the ETag will be for nothing
	return apply_filters(
		'pop_sw_add_etag_header',
		!GD_TemplateManager_Utils::page_requires_user_state()
	);
}