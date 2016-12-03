<?php

// add_filter('GD_Template_Processor_GFBlocks:newsletter:description', 'agendaurbana_gf_newsletter_description');
// function agendaurbana_gf_newsletter_description($description) {

// 	return get_the_title(POPTHEME_WASSUP_GF_PAGE_NEWSLETTER);
// }

add_filter('GD_Template_Processor_GFBlocks:newsletter:titletag', 'agendaurbana_gf_newsletter_titletag');
function agendaurbana_gf_newsletter_titletag($titletag) {

	return 'h4';
}

add_filter('GD_Template_Processor_GFBlocks:newsletter:descriptionbottom', 'agendaurbana_gf_newsletter_descriptionbottom');
function agendaurbana_gf_newsletter_descriptionbottom($description) {

	// Remove this message
	return null;
}