<?php

add_filter('GD_Template_Processor_GFBlocks:newsletter:description', 'organikfundraising_gf_newsletter_description');
function organikfundraising_gf_newsletter_description($description) {

	return get_the_title(POPTHEME_WASSUP_GF_PAGE_NEWSLETTER);
}

add_filter('GD_Template_Processor_GFBlocks:newsletter:titletag', 'organikfundraising_gf_newsletter_titletag');
function organikfundraising_gf_newsletter_titletag($titletag) {

	return 'h4';
}

add_filter('GD_Template_Processor_GFBlocks:newsletter:descriptionbottom', 'organikfundraising_gf_newsletter_descriptionbottom');
function organikfundraising_gf_newsletter_descriptionbottom($description) {

	// Remove this message
	return null;
}