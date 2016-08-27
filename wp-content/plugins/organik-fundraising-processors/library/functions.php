<?php

/**---------------------------------------------------------------------------------------------------------------
 * functions.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PoPTheme_Wassup_Utils:welcome_title', 'ofp_welcome_title'); 
add_filter('GD_DataLoad_IOHandler_FrameTopSimplePageSection:document_title', 'ofp_welcome_title'); 
function ofp_welcome_title($welcometitle = '') {

	return sprintf(
		__('Fundraising for %s', 'organik-fundraising-processors'),
		get_bloginfo('name')
	);
}

add_filter('gd_get_document_title:elements', 'ofp_document_title_elements'); 
function ofp_document_title_elements($elements) {

	// Override the Title
	if ( is_home() || is_front_page() ) {
        $elements['site_name'] = ofp_welcome_title();
    }

    return $elements;
}
