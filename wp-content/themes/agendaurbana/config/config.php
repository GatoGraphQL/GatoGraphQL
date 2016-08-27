<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

// CreateUpdate Post Moderation: Do NOT moderate
//--------------------------------------------------------
define ('GD_CONF_CREATEUPDATEPOST_MODERATE', false);

// Override the pop-cache folder
// define ('POP_CACHE_DIR', WP_CONTENT_DIR.'/pop-cache/agendaurbana');

$description = __('%s on agendaurbana.org. Buenos Aires NO se vende.', 'agendaurbana');
define ('GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL', sprintf($description, __('View all %s', 'agendaurbana')));
define ('GD_CONSTANT_PLACEHOLDER_DESCRIPTIONADDYOUR', sprintf($description, __('Add your %s', 'agendaurbana')));


/**---------------------------------------------------------------------------------------------------------------
 * Styles
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PopThemeWassup_AAL_Template_Processor_BackgroundColorStyleLayouts:bgcolor', 'agendaurbana_aal_bgcolor', 100, 2);
function agendaurbana_aal_bgcolor($color, $template_id) {

	switch ($template_id) {

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:

			return '#15354f';

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:

			return '#42c1ec';
	}

	return $color;
}


/**---------------------------------------------------------------------------------------------------------------
 * pagesections.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_CustomPageSections:pagesection-top:socialmedias', 'agendaurbana_pagesectiontop_socialmedias');
function agendaurbana_pagesectiontop_socialmedias($socialmedias) {
	
	return array(
		// array(
		// 	'title' => '<i class="fa fa-fw fa-twitter"></i>@agendaurbana',
		// 	'link' => 'https://twitter.com/agendaurbana',
		// 	'name' => __('Twitter', 'agendaurbana'),
		// ),
		array(
			'title' => '<i class="fa fa-fw fa-envelope-o"></i>info@agendaurbana.org',
			'link' => 'mailto:info@agendaurbana.org',
			'name' => __('Email', 'agendaurbana'),
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
		__('Loading', 'agendaurbana')
	);
}

add_filter('gd_loading_msg', 'agendaurbana_loading_msg');
function agendaurbana_loading_msg($msg) {
	
	return __('Loading the website', 'agendaurbana');
}

add_filter('gd_get_website_description', 'gd_get_website_description_impl', 10, 2);
function gd_get_website_description_impl($description, $addhomelink) {

	$home = 'agendaurbana.org';
	if ($addhomelink) {

		$home = sprintf(
			'<a href="%s">%s</a>',
			trailingslashit(home_url()),
			$home
		);
	}
	
	return sprintf(
		__('<strong>%s</strong> is a crowd-sourced platform to enable people to have effective participation in issues of citizen, community and/or neighborhood interest in the City of Buenos Aires (CABA) and its outskirts (constituting the Metropolitan Area Buenos Aires). <a href="%s">Find out more</a>.', 'agendaurbana'),
		$home,
		get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION)
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * email.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_email_notifications_email', 'gd_email_notifications_email_impl');
function gd_email_notifications_email_impl($email) {
	
	return 'losoviz@gmail.com';
}
add_filter('gd_email_newsletter_email', 'gd_email_newsletter_email_impl');
function gd_email_newsletter_email_impl($email) {
	
	return 'hola@agendaurbana.org';
}
add_filter('gd_email_info_email', 'gd_email_info_email_impl');
function gd_email_info_email_impl($email) {
	
	return 'info@agendaurbana.org';
}


/**---------------------------------------------------------------------------------------------------------------
 * googleanalytics.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_googleanalytics_key', 'gd_googleanalytics_key_impl');
function gd_googleanalytics_key_impl($key) {
	
	return 'UA-82084055-1';
}

/**---------------------------------------------------------------------------------------------------------------
 * socialmedia.php
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('gd_twitter_user', 'gd_twitter_user_impl');
// function gd_twitter_user_impl($key) {
	
// 	return '@agendaurbana11';
// }

/**---------------------------------------------------------------------------------------------------------------
 * header.php
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_header_site_description', 'gd_header_site_description_impl');
// Priority 11: after gd_header_page_description_impl
add_filter('gd_header_page_description', 'gd_header_site_description_impl', 11);
function gd_header_site_description_impl($description) {

	// Since this is the default description, check if it has not been set already
	// (eg: for the pages)
	if ($description) {
		return $description;
	}

	return __('agendaurbana.org is a crowd-sourced platform to enable people to have effective participation in issues of citizen, community and/or neighborhood interest in the City of Buenos Aires (CABA) and its outskirts (constituting the Metropolitan Area Buenos Aires).', 'agendaurbana');
}

add_filter('gd_header_page_description', 'gd_header_page_description_impl', 10, 2);
function gd_header_page_description_impl($description, $page_id) {

	switch ($page_id) {

		case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Highlights', 'agendaurbana'));
			break;

		case POPTHEME_WASSUP_PAGE_WEBPOSTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Posts', 'agendaurbana'));
			break;

		// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS:

		// 	$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Projects', 'agendaurbana'));
		// 	break;

		case AGENDAURBANA_PAGE_ARTICLES:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Articles', 'agendaurbana'));
			break;

		case AGENDAURBANA_PAGE_ANNOUNCEMENTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Announcements', 'agendaurbana'));
			break;

		case AGENDAURBANA_PAGE_RESOURCES:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Resources', 'agendaurbana'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Blog', 'agendaurbana'));
			break;
	}

	return $description;
}

add_filter('gd_get_favicon', 'agendaurbana_get_favicon');
function agendaurbana_get_favicon($favicon) {

	return AGENDAURBANA_URI.'/img/favicon.ico';
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
				1893, // Nidia
				1894, // Eduardo
				851, // Leo
			);

		case GD_TEMPLATE_BLOCK_WHOWEARE_VOLUNTEERS_SCROLL:

			return array(
				1891, // Agustín Rabinovich,
				1892, // Julia Ferrando
			);
	}

	return $ids;
}


/**---------------------------------------------------------------------------------------------------------------
 * images.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_get_logo', 'gd_get_logo_impl');
function gd_get_logo_impl($gd_logo) {

	$large = array(AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-logo.png', 360, 360, 'image/png');
	$large_inverse = array(AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-logo-fondoblanco.png', 360, 360, 'image/png');
	$large_white = array(AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-logo-fondoblanco.png', 360, 360, 'image/png');
	$small = array(AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-logo-chico.png', 150, 150, 'image/png');

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
	$gd_logo[2] = 300;
		
	return $gd_logo;
}
add_filter('gd_images_background', 'gd_images_background_impl');
function gd_images_background_impl($img) {

	// return AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-bsnosevende.png';
	return AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-muchedumbre-obelisco.png';
}
add_filter('gd_images_welcome', 'gd_images_welcome_impl');
function gd_images_welcome_impl($img) {

	// return AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-bsnosevende.png';
	return AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-muchedumbre-obelisco.png';
}

/**---------------------------------------------------------------------------------------------------------------
 * Link Categories
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('wassup_categories', 'agendaurbanatheme_categories');
add_filter('wassup_individualinterests', 'agendaurbanatheme_categories');
add_filter('wassup_organizationcategories', 'agendaurbanatheme_categories');
function agendaurbanatheme_categories($categories) {

	$categories = array_merge(
		$categories,
		array(
			sanitize_title('Advocacy') => __('Advocacy', 'agendaurbana'),
			sanitize_title('Agriculture') => __('Agriculture', 'agendaurbana'),
			sanitize_title('Civil Rights') => __('Civil Rights', 'agendaurbana'),
			sanitize_title('Corruption') => __('Corruption', 'agendaurbana'),
			sanitize_title('Discrimination') => __('Discrimination', 'agendaurbana'),
			sanitize_title('Economics') => __('Economics', 'agendaurbana'),
			sanitize_title('Education') => __('Education', 'agendaurbana'),
			sanitize_title('Employment') => __('Employment', 'agendaurbana'),
			sanitize_title('Energy') => __('Energy', 'agendaurbana'),
			sanitize_title('Environment') => __('Environment', 'agendaurbana'),
			sanitize_title('Food') => __('Food', 'agendaurbana'),
			sanitize_title('Health') => __('Health', 'agendaurbana'),
			sanitize_title('Housing') => __('Housing', 'agendaurbana'),
			sanitize_title('Justice') => __('Justice', 'agendaurbana'),
			sanitize_title('Law') => __('Law', 'agendaurbana'),
			sanitize_title('Migration') => __('Migration', 'agendaurbana'),
			sanitize_title('Military') => __('Military', 'agendaurbana'),
			sanitize_title('Politics') => __('Politics', 'agendaurbana'),
			sanitize_title('Poverty') => __('Poverty', 'agendaurbana'),
			sanitize_title('Religion') => __('Religion', 'agendaurbana'),
			sanitize_title('Science/technology') => __('Science/technology', 'agendaurbana'),
			sanitize_title('Social Welfare') => __('Social Welfare', 'agendaurbana'),
			sanitize_title('Others') => __('Others', 'agendaurbana'),
		)
	);

	return $categories;
}

add_filter('wassup_organizationtypes', 'agendaurbanatheme_organizationtypes');
function agendaurbanatheme_organizationtypes($types) {

	return array(
		sanitize_title('Association') => __('Association', 'agendaurbana'),
		sanitize_title('Community-based organisation') => __('Community-based organisation', 'agendaurbana'),
		sanitize_title('Cooperative') => __('Cooperative', 'agendaurbana'),
		sanitize_title('Corporation/Business') => __('Corporation/Business', 'agendaurbana'),
		sanitize_title('Foundation') => __('Foundation', 'agendaurbana'),
		sanitize_title('Governmental organisation') => __('Governmental organisation', 'agendaurbana'),
		sanitize_title('Informal economy') => __('Informal economy', 'agendaurbana'),
		sanitize_title('Mutual benefit society') => __('Mutual benefit society', 'agendaurbana'),
		sanitize_title('Non-governmental organisation') => __('Non-governmental organisation', 'agendaurbana'),
		sanitize_title('Research institute/University') => __('Research institute/University', 'agendaurbana'),
		sanitize_title('Social enterprise') => __('Social enterprise', 'agendaurbana'),
		sanitize_title('Other') => __('Other', 'agendaurbana'),
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * Embed Frame empty source
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_EmbedPreviewLayouts:get_frame_src', 'agendaurbanatheme_embedemptysource');
function agendaurbanatheme_embedemptysource($src) {

	return AGENDAURBANA_URI.'/img/iframebg.jpg';
}