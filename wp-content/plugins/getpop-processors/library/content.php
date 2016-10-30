<?php

// Override the title on the black bar on the top, for every and any page first accessed
add_filter('GD_DataLoad_IOHandler_FrameTopSimplePageSection:document_title', 'getpop_processors_title'); 
function getpop_processors_title($welcometitle = '') {

	return sprintf(
		__('%s | %s', 'getpop-processors'),
		get_bloginfo('name'),
		get_bloginfo('description')
	);
}