<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

// CreateUpdate Post Moderation: Do NOT moderate
//--------------------------------------------------------
define ('GD_CONF_CREATEUPDATEPOST_MODERATE', false);

// // Override the pop-cache folder
// define ('POP_CACHE_DIR', WP_CONTENT_DIR.'/pop-cache/getpop');

$description = __('%s on the PoP framework. Break the information monopoly', 'getpop-demo');
define ('GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL', sprintf($description, __('View all %s', 'getpop-demo')));
define ('GD_CONSTANT_PLACEHOLDER_DESCRIPTIONADDYOUR', sprintf($description, __('Add your %s', 'getpop-demo')));


/**---------------------------------------------------------------------------------------------------------------
 * Styles
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PopThemeWassup_AAL_Template_Processor_BackgroundColorStyleLayouts:bgcolor', 'getpopdemo_aal_bgcolor', 100, 2);
function getpopdemo_aal_bgcolor($color, $template_id) {

	switch ($template_id) {

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:

			return '#f4ce79';

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:

			return '#db902e';
	}

	return $color;
}


/**---------------------------------------------------------------------------------------------------------------
 * pagesections.php
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('GD_Template_Processor_CustomPageSections:pagesection-top:titles', 'getpopdemo_pagesectiontop_titles');
// function getpopdemo_pagesectiontop_titles($titles) {
	
// 	$titles['footer'] = sprintf(
// 		__('Powered by <a href="%s">the PoP framework</a>', 'getpop-demo'),
// 		'https://getpop.org'
// 	);
// 	return $titles;
// }

add_filter('GD_Template_Processor_CustomPageSections:pagesection-top:socialmedias', 'getpopdemo_pagesectiontop_socialmedias');
function getpopdemo_pagesectiontop_socialmedias($socialmedias) {
	
	return array(
		// array(
		// 	'title' => '<i class="fa fa-fw fa-twitter"></i>@GetPoPGlobal',
		// 	'link' => 'https://twitter.com/GetPoPGlobal',
		// 	'name' => __('Twitter', 'getpop-demo'),
		// ),
		array(
			'title' => '<i class="fa fa-fw fa-envelope-o"></i>info@getpop.org',
			'link' => 'mailto:info@getpop.org',
			'name' => __('Email', 'getpop-demo'),
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
		__('Loading', 'getpop-demo')
	);
}

add_filter('gd_loading_msg', 'getpopdemo_loading_msg');
function getpopdemo_loading_msg($msg) {
	
	return __('Loading the website', 'getpop-demo');
}

add_filter('gd_get_website_description', 'gd_get_website_description_impl', 10, 2);
function gd_get_website_description_impl($description, $addhomelink) {

	$home = get_bloginfo('name'); //'PoP framework';
	if ($addhomelink) {

		$home = sprintf(
			'<a href="%s">%s</a>',
			trailingslashit(home_url()),
			$home
		);
	}

	return sprintf(
		__('Use <strong>%s</strong> to see how a PoP website works. Go ahead and play with it: register, create a post, add comments, follow other users, recommend content...', 'getpop-demo'),
		$home
	);
	// return sprintf(
	// 	__('Use <strong>%s</strong> to see how a PoP website works, try <a href="%s">all the things you can do with it</a>. Go ahead and play with it: create a user account, create a post, add comments, follow other users, recommend content...', 'getpop-demo'),
	// 	$home,
	// 	get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_HOWTOUSEWEBSITE)
	// );
}

/**---------------------------------------------------------------------------------------------------------------
 * email.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_email_notifications_email', 'gd_email_notifications_email_impl');
function gd_email_notifications_email_impl($email) {
	
	return 'losoviz@gmail.com';//'notifications@getpop.org';
}
add_filter('gd_email_newsletter_email', 'gd_email_newsletter_email_impl');
function gd_email_newsletter_email_impl($email) {
	
	return 'hello@getpop.org';
}
add_filter('gd_email_info_email', 'gd_email_info_email_impl');
function gd_email_info_email_impl($email) {
	
	return 'info@getpop.org';
}


/**---------------------------------------------------------------------------------------------------------------
 * googleanalytics.php
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('gd_googleanalytics_key', 'gd_googleanalytics_key_impl');
// function gd_googleanalytics_key_impl($key) {
	
// 	return 'UA-79172962-1';
// }

/**---------------------------------------------------------------------------------------------------------------
 * socialmedia.php
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('gd_twitter_user', 'gd_twitter_user_impl');
// function gd_twitter_user_impl($key) {
	
// 	return '@getpop';
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

	return sprintf(
		__('%s is open source software which aims to decentralize the content flow and break the information monopoly from large internet corporations.', 'getpop-demo'),
		get_bloginfo('name')
	);
}

add_filter('gd_header_page_description', 'gd_header_page_description_impl', 10, 2);
function gd_header_page_description_impl($description, $page_id) {

	switch ($page_id) {

		case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Highlights', 'getpop-demo'));
			break;

		case POPTHEME_WASSUP_PAGE_WEBPOSTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Posts', 'getpop-demo'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Projects', 'getpop-demo'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Stories', 'getpop-demo'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Articles', 'getpop-demo'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Announcements', 'getpop-demo'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Featured Articles', 'getpop-demo'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('PoP Blog', 'getpop-demo'));
			break;

		// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_RESOURCES:

		// 	$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Resources', 'getpop-demo'));
		// 	break;
	}

	return $description;
}

add_filter('gd_get_favicon', 'getpopdemo_get_favicon');
function getpopdemo_get_favicon($favicon) {

	return GETPOPDEMO_ASSETS_URI.'/img/favicon.ico';
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
 * images.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_get_logo', 'gd_get_logo_impl');
function gd_get_logo_impl($gd_logo) {

	$large = array(GETPOPDEMO_ASSETS_URI.'/img/popdemo-logo.png', 640, 250, 'image/png');
	$large_inverse = array(GETPOPDEMO_ASSETS_URI.'/img/popdemo-logo.png', 640, 250, 'image/png');
	$large_white = array(GETPOPDEMO_ASSETS_URI.'/img/popdemo-logo-whitebg.png', 640, 250, 'image/png');
	$small = array(GETPOPDEMO_ASSETS_URI.'/img/pop-logo-favicon.png', 150, 150, 'image/png');

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
add_filter('gd_images_loading', 'gd_images_background_impl');
function gd_images_background_impl($img) {

	return GETPOPDEMO_ASSETS_URI.'/img/popdemo-logo-horizontal.png';
}

/**---------------------------------------------------------------------------------------------------------------
 * Link Categories
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('wassup_categories', 'getpoptheme_categories');
add_filter('wassup_individualinterests', 'getpoptheme_categories');
function getpoptheme_categories($categories) {

	$categories = array_merge(
		$categories,
		array(
			sanitize_title('Animals & Pets') => __('Animals & Pets', 'getpop-demo'),
			sanitize_title('Art & Photography') => __('Art & Photography', 'getpop-demo'),
			sanitize_title('Business & Finance') => __('Business & Finance', 'getpop-demo'),
			sanitize_title('Computers & Electronics') => __('Computers & Electronics', 'getpop-demo'),
			sanitize_title('Cooking, Food & Beverage') => __('Cooking, Food & Beverage', 'getpop-demo'),
			sanitize_title('Education') => __('Education', 'getpop-demo'),
			sanitize_title('Entertainment & TV') => __('Entertainment & TV', 'getpop-demo'),
			sanitize_title('Fashion & Style') => __('Fashion & Style', 'getpop-demo'),
			sanitize_title('Health & Fitness') => __('Health & Fitness', 'getpop-demo'),
			sanitize_title('Home & Gardening') => __('Home & Gardening', 'getpop-demo'),
			sanitize_title('International') => __('International', 'getpop-demo'),
			sanitize_title('Lifestyle') => __('Lifestyle', 'getpop-demo'),
			sanitize_title('Medical') => __('Medical', 'getpop-demo'),
			sanitize_title('Music') => __('Music', 'getpop-demo'),
			sanitize_title('News & Politics') => __('News & Politics', 'getpop-demo'),
			sanitize_title('Religion') => __('Religion', 'getpop-demo'),
			sanitize_title('Science & Nature') => __('Science & Nature', 'getpop-demo'),
			sanitize_title('Sports & Recreation') => __('Sports & Recreation', 'getpop-demo'),
			sanitize_title('Other') => __('Other', 'getpop-demo'),
		)
	);

	return $categories;
}

add_filter('wassup_organizationcategories', 'getpoptheme_organizationcategories');
function getpoptheme_organizationcategories($categories) {

	$categories = array_merge(
		$categories,
		array(
			sanitize_title('Clean energy') => __('Clean energy', 'ripess'),
			sanitize_title('Community building') => __('Community building', 'ripess'),
			sanitize_title('Decent work') => __('Decent work', 'ripess'),
			sanitize_title('Education') => __('Education', 'ripess'),
			sanitize_title('Environmental protection') => __('Environmental protection', 'ripess'),
			sanitize_title('Fair trade') => __('Fair trade', 'ripess'),
			sanitize_title('Food and agriculture') => __('Food and agriculture', 'ripess'),
			sanitize_title('Healthcare') => __('Healthcare', 'ripess'),
			sanitize_title('Human rights') => __('Human rights', 'ripess'),
			sanitize_title('Legal frameworks') => __('Legal frameworks', 'ripess'),
			sanitize_title('Local and alternative currencies') => __('Local and alternative currencies', 'ripess'),
			sanitize_title('Marginalised communities') => __('Marginalised communities', 'ripess'),
			sanitize_title('Media and public awareness') => __('Media and public awareness', 'ripess'),
			sanitize_title('Open source technologies') => __('Open source technologies', 'ripess'),
			sanitize_title('Policies for SSE') => __('Policies for SSE', 'ripess'),
			sanitize_title('Poverty alleviation') => __('Poverty alleviation', 'ripess'),
			sanitize_title('Recycling/Waste management') => __('Recycling/Waste management', 'ripess'),
			sanitize_title('Research') => __('Research', 'ripess'),
			sanitize_title('Responsible production') => __('Responsible production', 'ripess'),
			sanitize_title('Responsible tourism') => __('Responsible tourism', 'ripess'),
			sanitize_title('Rural development') => __('Rural development', 'ripess'),
			sanitize_title('Social welfare') => __('Social welfare', 'ripess'),
			sanitize_title('Solidarity finance') => __('Solidarity finance', 'ripess'),
			sanitize_title('Sustainable cities') => __('Sustainable cities', 'ripess'),
			sanitize_title('Women and youth empowerment') => __('Women and youth empowerment', 'ripess'),
			sanitize_title('Other') => __('Other', 'ripess'),
		)
	);

	return $categories;
}

add_filter('wassup_organizationtypes', 'getpoptheme_organizationtypes');
function getpoptheme_organizationtypes($types) {

	return array(
		sanitize_title('Association') => __('Association', 'getpop-demo'),
		sanitize_title('Community-based organisation') => __('Community-based organisation', 'getpop-demo'),
		sanitize_title('Cooperative') => __('Cooperative', 'getpop-demo'),
		sanitize_title('Corporation/Business') => __('Corporation/Business', 'getpop-demo'),
		sanitize_title('Foundation') => __('Foundation', 'getpop-demo'),
		sanitize_title('Governmental organisation') => __('Governmental organisation', 'getpop-demo'),
		sanitize_title('Informal economy') => __('Informal economy', 'getpop-demo'),
		sanitize_title('Mutual benefit society') => __('Mutual benefit society', 'getpop-demo'),
		sanitize_title('Non-governmental organisation') => __('Non-governmental organisation', 'getpop-demo'),
		sanitize_title('Research institute/University') => __('Research institute/University', 'getpop-demo'),
		sanitize_title('Social enterprise') => __('Social enterprise', 'getpop-demo'),
		sanitize_title('Other') => __('Other', 'getpop-demo'),
	);
}


/**---------------------------------------------------------------------------------------------------------------
 * Embed Frame empty source
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_EmbedPreviewLayouts:get_frame_src', 'getpoptheme_embedemptysource');
function getpoptheme_embedemptysource($src) {

	return GETPOPDEMO_ASSETS_URI.'/img/iframebg.jpg';
}