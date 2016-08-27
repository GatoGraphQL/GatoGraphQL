<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

// CreateUpdate Post Moderation: Do NOT moderate
//--------------------------------------------------------
define ('GD_CONF_CREATEUPDATEPOST_MODERATE', false);

// Override the pop-cache folder
// define ('POP_CACHE_DIR', WP_CONTENT_DIR.'/pop-cache/tppdebate');

$description = __('%s on TPPDebate.org. What does everyone think about the Trans-Pacific Partnership Agreement (TPP)? What is your stance?', 'tppdebate');
define ('GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL', sprintf($description, __('View all %s', 'tppdebate')));
define ('GD_CONSTANT_PLACEHOLDER_DESCRIPTIONADDYOUR', sprintf($description, __('Add your %s', 'tppdebate')));



/**---------------------------------------------------------------------------------------------------------------
 * Home Welcome
 * ---------------------------------------------------------------------------------------------------------------*/ 
// add_filter('GD_Template_Processor_Codes:home_welcome:content', 'votingprocessors_code_homewelcome_content');
// function votingprocessors_code_homewelcome_content($content) {

// 	return array(
// 		'pages' => array(
// 			POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES => null,
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION,
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY,
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT,
// 			POPTHEME_WASSUP_EM_PAGE_EVENTS => POPTHEME_WASSUP_EM_PAGE_ADDEVENT,
// 		),
// 		'descriptions' => array(
// 			POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES => __('What do people and organizations think of TPP, do they overall support it or oppose it?', 'tppdebate'),
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS => __('Anything and everything: what do you want to express concerning TPP?', 'tppdebate'),
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES => __('How will TPP affect your life? Will it be positive or negative? Share your personal experience with the world.', 'tppdebate'),
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS => __('Have a press release stating your position on TPP? Post it here.', 'tppdebate'),
// 			POPTHEME_WASSUP_EM_PAGE_EVENTS => __('All kind of events concerning TPP: public debates, conferences, awareness gatherings, etc.', 'tppdebate'),
// 		)
// 	);
// }

/**---------------------------------------------------------------------------------------------------------------
 * Override with custom blocks
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_CustomBlockGroups:blocks:whoweare', 'tppdebate_blocks_whoweare', 20);
function tppdebate_blocks_whoweare($blocks) {

	return array(
		GD_TEMPLATE_BLOCK_WHOWEARE_CORE_SCROLL,
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * Block titles
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PoPTheme_Wassup_VotingProcessors_Utils:latestvotes_title', 'tppdebate_latestvotes_title');
function tppdebate_latestvotes_title($title) {

	return __('Latest thoughts on TPP', 'tppdebate');
}

add_filter('PoPTheme_Wassup_VotingProcessors_Utils:whatisyourvote_title', 'tppdebate_whatisyourvote_title', 10, 2);
function tppdebate_whatisyourvote_title($title, $format) {

	if ($format == 'lc') {

		return __('what do you think about TPP?', 'tppdebate');		
	}

	return __('What do you think about TPP?', 'tppdebate');
}

/**---------------------------------------------------------------------------------------------------------------
 * Category names
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_catname', 'tppdebate_catname', 100, 3);
function tppdebate_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
		
			return __('Thought on TPP', 'tppdebate');
	}

	return $name;
}
add_filter('gd_format_catname', 'tppdebate_format_catname', 100, 3);
function tppdebate_format_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:

			if ($format == 'lc') {
				return __('thought on TPP', 'tppdebate');
			}
			elseif ($format == 'plural') {
				return __('Thoughts on TPP', 'tppdebate');
			}
			elseif ($format == 'plural-lc') {
				return __('thoughts on TPP', 'tppdebate');
			}
			break;
	}

	return $name;
}

add_filter('GD_FormInput_MultiStance:values', 'tppdebate_multistance_values');
function tppdebate_multistance_values($values) {

	$values[POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO] = __('Pro TPP', 'tppdebate');
	$values[POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST] = __('Against TPP', 'tppdebate');
	return $values;
}


/**---------------------------------------------------------------------------------------------------------------
 * Styles
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PopThemeWassup_AAL_Template_Processor_BackgroundColorStyleLayouts:bgcolor', 'tppdebate_aal_bgcolor', 100, 2);
function tppdebate_aal_bgcolor($color, $template_id) {

	switch ($template_id) {

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:

			return '#4da099';
	}

	return $color;
}


/**---------------------------------------------------------------------------------------------------------------
 * team.php
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_fixedscroll_user_ids', 'gd_fixedscroll_user_ids_impl', 10, 2);
function gd_fixedscroll_user_ids_impl($ids, $template_id) {

	switch ($template_id) {

		case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_DETAILS:
		case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_THUMBNAIL:
		case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST:
		case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_FULLVIEW:
		case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP:
		case GD_TEMPLATE_BLOCK_WHOWEARE_CORE_SCROLL:

			return array(
				866, // Jun-E
				851, // Leo
			);
	}

	return $ids;
}

/**---------------------------------------------------------------------------------------------------------------
 * pagesections.php
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('GD_Template_Processor_CustomPageSections:pagesection-top:titles', 'tpp_pagesectiontop_titles');
// function tpp_pagesectiontop_titles($titles) {
	
// 	$titles['footer'] = sprintf(
// 		__('An initiative by <a href="%s">%s</a>', 'tppdebate'),
// 		'https://www.mesym.com',
// 		'MESYM.com'
// 	);
// 	return $titles;
// }

add_filter('GD_Template_Processor_CustomPageSections:pagesection-top:socialmedias', 'tpp_pagesectiontop_socialmedias');
function tpp_pagesectiontop_socialmedias($socialmedias) {
	
	return array(
		array(
			'title' => '<i class="fa fa-fw fa-twitter"></i>@TPPDebate',
			'link' => 'https://twitter.com/TPPDebate',
			'name' => __('Twitter', 'tppdebate'),
		),
		array(
			'title' => '<i class="fa fa-fw fa-envelope-o"></i>info@tppdebate.org',
			'link' => 'mailto:info@tppdebate.org',
			'name' => __('Email', 'tppdebate'),
		),
	);
}


/**---------------------------------------------------------------------------------------------------------------
 * content.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_get_initial_document_title', 'gd_get_initial_document_title_impl');
function gd_get_initial_document_title_impl($title) {
	
	return sprintf('%s | %s',
		get_bloginfo('name'),
		__('Loading the debate', 'tppdebate')
	);
}

add_filter('gd_loading_msg', 'tpp_loading_msg');
function tpp_loading_msg($msg) {
	
	return __('Loading the debate', 'tppdebate');
}

add_filter('gd_get_website_description', 'gd_get_website_description_impl', 10, 2);
function gd_get_website_description_impl($description, $addhomelink) {

	$home = 'TPPDebate.org';
	if ($addhomelink) {

		$home = sprintf(
			'<a href="%s">%s</a>',
			trailingslashit(home_url()),
			$home
		);
	}
	
	return sprintf(
		__('<strong>%s</strong> is a crowd-sourced platform to debate the Trans-Pacific Partnership Agreement (TPP). What does everyone think about the TPP? What is your stance?', 'tppdebate'),
		$home
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * email.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_email_notifications_email', 'gd_email_notifications_email_impl');
function gd_email_notifications_email_impl($email) {
	
	return 'losoviz@gmail.com';//'notifications@tppdebate.org';
}
add_filter('gd_email_newsletter_email', 'gd_email_newsletter_email_impl');
function gd_email_newsletter_email_impl($email) {
	
	return 'hello@tppdebate.org';
}
add_filter('gd_email_info_email', 'gd_email_info_email_impl');
function gd_email_info_email_impl($email) {
	
	return 'info@tppdebate.org';
}


/**---------------------------------------------------------------------------------------------------------------
 * googleanalytics.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_googleanalytics_key', 'gd_googleanalytics_key_impl');
function gd_googleanalytics_key_impl($key) {
	
	return 'UA-71295786-1';
}

/**---------------------------------------------------------------------------------------------------------------
 * socialmedia.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_twitter_user', 'gd_twitter_user_impl');
function gd_twitter_user_impl($key) {
	
	return '@TPPDebate';
}

/**---------------------------------------------------------------------------------------------------------------
 * header.php
 * ---------------------------------------------------------------------------------------------------------------*/

// Priority 11: after gd_header_page_description_impl
add_filter('gd_header_site_description', 'gd_header_site_description_impl', 11);
add_filter('gd_header_page_description', 'gd_header_site_description_impl', 11);
function gd_header_site_description_impl($description) {

	// Since this is the default description, check if it has not been set already
	// (eg: for the pages)
	if ($description) {
		return $description;
	}

	return __('TPPDebate.org is a crowd-sourced platform to debate the Trans-Pacific Partnership Agreement (TPP). What does everyone think about the TPP? What is your stance?', 'tppdebate');
}

add_filter('gd_header_page_description', 'gd_header_page_description_impl', 10, 2);
function gd_header_page_description_impl($description, $page_id) {

	switch ($page_id) {

		case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Links', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Highlights', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_PAGE_WEBPOSTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Posts', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Thoughts on TPP', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Thoughts on TPP by organizations', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Thoughts on TPP by individuals', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Thoughts pro TPP', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Thoughts against TPP', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Thoughts neutral to TPP', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Stories', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Articles', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Announcements', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('TPPDebate Blog', 'tppdebate'));
			break;
	}

	return $description;
}


/**---------------------------------------------------------------------------------------------------------------
 * avatars.php
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_get_favicon', 'tppdebate_get_favicon');
function tppdebate_get_favicon($favicon) {

	return TPPDEBATE_ASSETS_URI.'/img/favicon.ico';
}

/**---------------------------------------------------------------------------------------------------------------
 * images.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_get_logo', 'gd_get_logo_impl');
function gd_get_logo_impl($gd_logo) {

	$large = array(TPPDEBATE_ASSETS_URI.'/img/tppdebate-logo.png', 1560, 512, 'image/png');
	$large_inverse = array(TPPDEBATE_ASSETS_URI.'/img/tppdebate-logo-inverse.png', 1560, 512, 'image/png');
	$large_white = array(TPPDEBATE_ASSETS_URI.'/img/tppdebate-logo-white.png', 1560, 512, 'image/png');
	$small = array(TPPDEBATE_ASSETS_URI.'/img/tppdebate-small-inverse.png', 146, 146, 'image/png');

	return array(
		'small' => $small,
		'large' => $large,
		'large-inverse' => $large_inverse,
		'large-white' => $large_white
	);
}
add_filter('gd_login_logo', 'gd_login_logo_impl');
function gd_login_logo_impl($gd_logo) {

	// Modify the dimensions of the logo for the wp logged out page (http://m3l.localhost/wp-login.php?loggedout=true)
	$gd_logo[1] = 300;
	$gd_logo[2] = 98;
		
	return $gd_logo;
}
add_filter('gd_images_background', 'gd_images_background_impl');
function gd_images_background_impl($img) {

	return TPPDEBATE_ASSETS_URI.'/img/background.png';
}
add_filter('gd_images_welcome', 'gd_images_welcome_impl');
function gd_images_welcome_impl($img) {

	return TPPDEBATE_ASSETS_URI.'/img/welcome.png';
}

/**---------------------------------------------------------------------------------------------------------------
 * Link Categories
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('wassup_categories', 'tpptheme_categories');
add_filter('wassup_linkcategories', 'tpptheme_categories');
add_filter('wassup_discussioncategories', 'tpptheme_categories');
add_filter('wassup_individualinterests', 'tpptheme_categories');
function tpptheme_categories($categories) {

	$categories = array_merge(
		$categories,
		array(
			sanitize_title('Climate Change') => __('Climate Change', 'tppdebate'),
			sanitize_title('Data Protection/Privacy') => __('Data Protection/Privacy', 'tppdebate'),
			sanitize_title('Development') => __('Development', 'tppdebate'),
			sanitize_title('Economy') => __('Economy', 'tppdebate'),
			sanitize_title('Electronic Commerce') => __('Electronic Commerce', 'tppdebate'),
			sanitize_title('Environment') => __('Environment', 'tppdebate'),
			sanitize_title('Financial Services') => __('Financial Services', 'tppdebate'),
			sanitize_title('Geopolitics') => __('Geopolitics', 'tppdebate'),
			sanitize_title('Government Procurement') => __('Government Procurement', 'tppdebate'),
			sanitize_title('Human Rights') => __('Human Rights', 'tppdebate'),
			sanitize_title('Intellectual Property') => __('Intellectual Property', 'tppdebate'),
			sanitize_title('Investment ') => __('Investment ', 'tppdebate'),
			sanitize_title('Investor-State Dispute Settlement (ISDS)') => __('Investor-State Dispute Settlement (ISDS)', 'tppdebate'),
			sanitize_title('Labour') => __('Labour', 'tppdebate'),
			sanitize_title('Law') => __('Law', 'tppdebate'),
			sanitize_title('Medicine and Health Care') => __('Medicine and Health Care', 'tppdebate'),
			sanitize_title('Negotiation/Implementation') => __('Negotiation/Implementation', 'tppdebate'),
			sanitize_title('Sanitary and Phytosanitary Measures') => __('Sanitary and Phytosanitary Measures', 'tppdebate'),
			sanitize_title('Service-sector Regulation') => __('Service-sector Regulation', 'tppdebate'),
			sanitize_title('Sovereignty') => __('Sovereignty', 'tppdebate'),
			sanitize_title('Telecommunications') => __('Telecommunications', 'tppdebate'),
			sanitize_title('Textiles and Apparel') => __('Textiles and Apparel', 'tppdebate'),
			sanitize_title('Trade') => __('Trade', 'tppdebate'),
			sanitize_title('Transparency and Anti-Corruption') => __('Transparency and Anti-Corruption', 'tppdebate'),
		)
	);

	return $categories;
}

add_filter('wassup_organizationcategories', 'tpptheme_organizationcategories');
function tpptheme_organizationcategories($categories) {

	$categories = array_merge(
		$categories,
		array(
			sanitize_title('Advocacy') => __('Advocacy', 'tppdebate'),
			sanitize_title('Civil rights activities') => __('Civil rights activities', 'tppdebate'),
			sanitize_title('Cultural/Educational') => __('Cultural/Educational', 'tppdebate'),
			sanitize_title('Energy') => __('Energy', 'tppdebate'),
			sanitize_title('Environmental activities') => __('Environmental activities', 'tppdebate'),
			sanitize_title('Finance') => __('Finance', 'tppdebate'),
			sanitize_title('Food services') => __('Food services', 'tppdebate'),
			sanitize_title('Health services') => __('Health services', 'tppdebate'),
			sanitize_title('Legal services') => __('Legal services', 'tppdebate'),
			sanitize_title('Legislative/Political activities') => __('Legislative/Political activities', 'tppdebate'),
			sanitize_title('Religious activities') => __('Religious activities', 'tppdebate'),
			sanitize_title('Scientific research') => __('Scientific research', 'tppdebate'),
			sanitize_title('Technology') => __('Technology', 'tppdebate'),
			sanitize_title('Tourism') => __('Tourism', 'tppdebate'),
			sanitize_title('Transportation/Urban planning') => __('Transportation/Urban planning', 'tppdebate'),
			sanitize_title('Other') => __('Other', 'tppdebate'),
		)
	);

	return $categories;
}

/**---------------------------------------------------------------------------------------------------------------
 * Embed Frame empty source
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_EmbedPreviewLayouts:get_frame_src', 'mesymtheme_embedemptysource');
function mesymtheme_embedemptysource($src) {

	return TPPDEBATE_ASSETS_URI.'/img/iframebg.jpg';
}