<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

// CreateUpdate Post Moderation: Do NOT moderate
//--------------------------------------------------------
define ('GD_CONF_CREATEUPDATEPOST_MODERATE', false);

// Override the pop-cache folder
// define ('POP_CACHE_DIR', WP_CONTENT_DIR.'/pop-cache/mesym');

// Override the Metakey Prefix for backwards compatibility
// define ('POP_METAKEY_PREFIX', 'gd_');

$description = __('%s on MESYM.com. Join our environmental community and find out who is doing what, where and when.', 'mesym');
define ('GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL', sprintf($description, __('View all %s', 'mesym')));
define ('GD_CONSTANT_PLACEHOLDER_DESCRIPTIONADDYOUR', sprintf($description, __('Add your %s', 'mesym')));

/**---------------------------------------------------------------------------------------------------------------
 * Home Welcome
 * ---------------------------------------------------------------------------------------------------------------*/ 
// add_filter('GD_Template_Processor_Codes:home_welcome:content', 'gd_custom_code_homewelcome_content');
// function gd_custom_code_homewelcome_content($content) {

// 	return array(
// 		'pages' => array(
// 			// POPTHEME_WASSUP_PAGE_WEBPOSTLINKS => POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK,
// 			POPTHEME_WASSUP_EM_PAGE_EVENTS => POPTHEME_WASSUP_EM_PAGE_ADDEVENT,
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOST,
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY,
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT,
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION,
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED => null,
// 			POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS => null,
// 			POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS => null,
// 		),
// 		'descriptions' => array(
// 			// POPTHEME_WASSUP_PAGE_WEBPOSTLINKS => __('A compilation of links about environmental issues from all over the internet.', 'mesym'),
// 			POPTHEME_WASSUP_EM_PAGE_EVENTS => __('What\'s going on all over Malaysia.', 'mesym'),
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS => __('Volunteer for projects from many NGOs, or even start your own!', 'mesym'),
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES => __('After volunteering, post your photo blog, share your experience with the world.', 'mesym'),
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS => __('Have vacancies? Need green-minded people? Post it here.', 'mesym'),
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS => __('Anything and everything: what do you want to express concerning the environment situation in Malaysia?', 'mesym'),
// 			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED => __('Interviews to environmental heroes, research on important issues, and more.', 'mesym'),
// 			POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS => __('Browse the living database of Organizations and find out who is doing what, when and where.', 'mesym'),
// 			POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS => __('Anyone willing to take part in environmental action can feel at home in MESYM.', 'mesym'),
// 		)
// 	);
// }

/**---------------------------------------------------------------------------------------------------------------
 * Codes
 * ---------------------------------------------------------------------------------------------------------------*/ 
add_filter('GD_Template_Processor_Codes:get_code:message', 'mesym_code_message', 10, 2);
function mesym_code_message($message, $template_id) {

	switch ($template_id) {

		case GD_TEMPLATE_CODE_OURSPONSORSINTRO:

			return __('Many thanks to our sponsors and supporters, who help us do our bit of environmental action.', 'mesym');

		case GD_TEMPLATE_CODE_VOLUNTEERWITHUSINTRO:

			return __('Many thanks to our volunteers for helping us do our bit of environmental action.', 'mesym');
	}

	return $message;
}


/**---------------------------------------------------------------------------------------------------------------
 * Styles
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PopThemeWassup_AAL_Template_Processor_BackgroundColorStyleLayouts:bgcolor', 'wassup_aal_bgcolor', 100, 2);
function wassup_aal_bgcolor($color, $template_id) {

	switch ($template_id) {

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:

			return '#454c0f';

		case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:

			return '#6acada';
	}

	return $color;
}

/**---------------------------------------------------------------------------------------------------------------
 * content.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_get_initial_document_title', 'gd_get_initial_document_title_impl');
function gd_get_initial_document_title_impl($title) {
	
	return sprintf('%s | %s',
		get_bloginfo('name'),
		__('Loading pure awesomeness', 'mesym')
	);
}

add_filter('gd_get_website_description', 'gd_get_website_description_impl', 10, 2);
function gd_get_website_description_impl($description, $addhomelink) {

	$home = 'MESYM.com';
	if ($addhomelink) {

		$home = sprintf(
			'<a href="%s">%s</a>',
			trailingslashit(home_url()),
			$home
		);
	}
	
	return sprintf(
		__('<strong>%s</strong> is a crowd-sourced platform and a living database for environmental movements in Malaysia. There are many good actions being done out there. Our goal is to bring them together. <a href="%s">We connect the green dots</a>.', 'mesym'),
		$home,
		get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION)
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * email.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_email_notifications_email', 'gd_email_notifications_email_impl');
function gd_email_notifications_email_impl($email) {
	
	return 'losoviz@gmail.com';//'notifications@mesym.com';
}
add_filter('gd_email_newsletter_email', 'gd_email_newsletter_email_impl');
function gd_email_newsletter_email_impl($email) {
	
	return 'hello@mesym.com';
}
add_filter('gd_email_info_email', 'gd_email_info_email_impl');
function gd_email_info_email_impl($email) {
	
	return 'info@mesym.com';
}


/**---------------------------------------------------------------------------------------------------------------
 * googleanalytics.php
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('gd_googleanalytics_key', 'gd_googleanalytics_key_impl');
// function gd_googleanalytics_key_impl($key) {
	
// 	return 'UA-40228107-1';
// }

/**---------------------------------------------------------------------------------------------------------------
 * socialmedia.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_twitter_user', 'gd_twitter_user_impl');
function gd_twitter_user_impl($key) {
	
	return '@mesym11';
}

/**---------------------------------------------------------------------------------------------------------------
 * pagesections.php
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('GD_Template_Processor_CustomPageSections:pagesection-top:titles', 'wassup_pagesectiontop_titles');
// function wassup_pagesectiontop_titles($titles) {
	
// 	$titles['footer'] = __('The website is currently in beta, please forgive all the bugs.', 'mesym');
// 	return $titles;
// }

add_filter('GD_Template_Processor_CustomPageSections:pagesection-top:socialmedias', 'wassup_pagesectiontop_socialmedias');
function wassup_pagesectiontop_socialmedias($socialmedias) {
	
	return array(
		array(
			'title' => '<i class="fa fa-fw fa-facebook"></i>fb.com/mesym11',
			'link' => 'https://www.facebook.com/MESYM11',
			'name' => __('Facebook', 'mesym'),
		),
		array(
			'title' => '<i class="fa fa-fw fa-twitter"></i>@mesym11',
			'link' => 'https://twitter.com/mesym11',
			'name' => __('Twitter', 'mesym'),
		),
		array(
			'title' => '<i class="fa fa-fw fa-envelope-o"></i>info@mesym.com',
			'link' => 'mailto:info@mesym.com',
			'name' => __('Email', 'mesym'),
		),
	);
}

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

	return __('MESYM.com is a platform for environmental movements in Malaysia. There are many good actions being done out there. Our goal is to bring them together. We connect the green dots.', 'mesym');
}

add_filter('gd_header_page_description', 'gd_header_page_description_impl', 10, 2);
function gd_header_page_description_impl($description, $page_id) {

	switch ($page_id) {

		case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Environmental Links', 'mesym'));
			break;

		case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Environmental Highlights', 'mesym'));
			break;

		case POPTHEME_WASSUP_PAGE_WEBPOSTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Posts', 'mesym'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Environmental Projects', 'mesym'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Environmental Stories', 'mesym'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Environmental Discussions', 'mesym'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Environmental Announcements', 'mesym'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Featured Articles', 'mesym'));
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('MESYM Blogs', 'mesym'));
			break;

		// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_RESOURCES:

		// 	$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Environmental Resources', 'mesym'));
		// 	break;
	}

	return $description;
}


/**---------------------------------------------------------------------------------------------------------------
 * avatars.php
 * ---------------------------------------------------------------------------------------------------------------*/

// add_filter('gd_get_default_avatar', 'gd_get_default_avatar_impl');
// function gd_get_default_avatar_impl($avatar) {

// 	return MESYM_ASSETS_URI.'/img/mesym-avatar.jpg';
// }

add_filter('gd_get_favicon', 'wassup_get_favicon');
function wassup_get_favicon($favicon) {

	return MESYM_ASSETS_URI.'/img/favicon.ico';
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

		case GD_TEMPLATE_BLOCK_WHOWEARE_ADVISORS_SCROLL:

			return array(
				943, // Alizan
				966 // Yasmin
			);

		case GD_TEMPLATE_BLOCK_WHOWEARE_VOLUNTEERS_SCROLL:

			return array(
				1116, // Xiwen
				940, // Chen Yee
				1711, // Ambika
				// 1703, // Priya
				962, // Eva
				1615, // Mathi
				983, // Emily
				921, // Sheena
				992, // Nicolas Salocin
				991 // Chris Chan
			);

		case GD_TEMPLATE_BLOCK_OURSPONSORS_SCROLL:

			return array(
				1046, // IEN
			);

		case GD_TEMPLATE_BLOCK_OURSUPPORTERS_SCROLL:

			return array(
				962, // Eva
			);

		case GD_TEMPLATE_BLOCK_OURSPONSORS_TOPNAV_SCROLL:

			return array(
				1046, // IEN
				// 1023, // Gregers
			);
	}

	return $ids;
}


/**---------------------------------------------------------------------------------------------------------------
 * images.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_get_logo', 'gd_get_logo_impl');
function gd_get_logo_impl($gd_logo) {

	$large = array(MESYM_ASSETS_URI.'/img/mesym-logo.png', 500, 261, 'image/png');
	$large_inverse = array(MESYM_ASSETS_URI.'/img/mesym-logo-inverse.png', 600, 310, 'image/png');
	$large_white = array(MESYM_ASSETS_URI.'/img/mesym-logo.jpg', 500, 320, 'image/jpeg');
	$small = array(MESYM_ASSETS_URI.'/img/mesym-logo-3dots.png', 150, 150, 'image/png');

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
	$gd_logo[2] = 156;
		
	return $gd_logo;
}
add_filter('gd_images_background', 'gd_images_background_impl');
// add_filter('gd_images_welcome', 'gd_images_background_impl');
function gd_images_background_impl($gd_logo) {

	return MESYM_ASSETS_URI.'/img/start-spread-join.jpg';
}

/**---------------------------------------------------------------------------------------------------------------
 * Link Categories
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('wassup_categories', 'mesymtheme_categories');
add_filter('wassup_linkcategories', 'mesymtheme_categories');
add_filter('wassup_discussioncategories', 'mesymtheme_categories');
add_filter('wassup_individualinterests', 'mesymtheme_categories');
add_filter('wassup_projectcategories', 'mesymtheme_projectcategories');
function mesymtheme_categories($categories) {

	$categories = array_merge(
		$categories,
		array(
			sanitize_title('Activism') => __('Activism', 'mesym'),
			sanitize_title('Advocacy') => __('Advocacy', 'mesym'),
			sanitize_title('Biodiversity') => __('Biodiversity', 'mesym'),
			sanitize_title('Built environment') => __('Built environment', 'mesym'),
			sanitize_title('Business') => __('Business', 'mesym'),
			sanitize_title('Clean technology') => __('Clean technology', 'mesym'),
			sanitize_title('Climate change') => __('Climate change', 'mesym'),
			sanitize_title('Conservation/Protection') => __('Conservation/Protection', 'mesym'),
			sanitize_title('CSR/CER') => __('CSR/CER', 'mesym'),
			sanitize_title('Economics') => __('Economics', 'mesym'),
			sanitize_title('Ecotourism') => __('Ecotourism', 'mesym'),
			sanitize_title('Education') => __('Education', 'mesym'),
			sanitize_title('Empowerment') => __('Empowerment', 'mesym'),
			sanitize_title('Energy') => __('Energy', 'mesym'),
			sanitize_title('Food') => __('Food', 'mesym'),
			sanitize_title('Lifestyle') => __('Lifestyle', 'mesym'),
			sanitize_title('Population growth') => __('Population growth', 'mesym'),
			sanitize_title('Public awareness') => __('Public awareness', 'mesym'),
			sanitize_title('Social welfare') => __('Social welfare', 'mesym'),
			sanitize_title('Sustainability') => __('Sustainability', 'mesym'),
			sanitize_title('Technology') => __('Technology', 'mesym'),
			sanitize_title('Transportation') => __('Transportation', 'mesym'),
			sanitize_title('Urban planning') => __('Urban planning', 'mesym'),
			sanitize_title('Waste') => __('Waste', 'mesym'),
			sanitize_title('Water') => __('Water', 'mesym'),
			sanitize_title('Other') => __('Other', 'mesym'),
		)
	);

	return $categories;
}

// add_filter('wassup_projectcategories', 'mesymtheme_projectcategories');
// function mesymtheme_projectcategories($categories) {

// 	return array_merge(
// 		$categories,
// 		array(
// 			__('Advocacy', 'mesym'),
// 			__('Biodiversity', 'mesym'),
// 			__('Built environment','mesym'),
// 			__('Clean technology','mesym'),
// 			__('Climate change', 'mesym'),
// 			__('Conservation/Protection','mesym'),
// 			__('CSR/CER', 'mesym'),
// 			__('Ecotourism', 'mesym'),
// 			__('Education', 'mesym'),
// 			__('Empowerment','mesym'),
// 			__('Energy', 'mesym'),
// 			__('Food','mesym'),
// 			__('Population growth', 'mesym'),
// 			__('Public awareness', 'mesym'),
// 			__('Social welfare','mesym'),
// 			__('Sustainability','mesym'),
// 			__('Transportation','mesym'),
// 			__('Urban planning','mesym'),
// 			__('Waste', 'mesym'),
// 			__('Water', 'mesym'),
// 			__('Other', 'mesym'),
// 		)
// 	);
// }

add_filter('wassup_organizationcategories', 'mesymtheme_organizationcategories');
function mesymtheme_organizationcategories($categories) {

	$categories = array_merge(
		$categories,
		array(
			sanitize_title('Advocacy') => __('Advocacy', 'mesym'),
			sanitize_title('Biodiversity') => __('Biodiversity', 'mesym'),
			sanitize_title('Built environment') => __('Built environment', 'mesym'),
			sanitize_title('Clean technology') => __('Clean technology', 'mesym'),
			sanitize_title('Climate change') => __('Climate change', 'mesym'),
			sanitize_title('Conservation/Protection') => __('Conservation/Protection', 'mesym'),
			sanitize_title('CSR/CER') => __('CSR/CER', 'mesym'),
			sanitize_title('Ecotourism') => __('Ecotourism', 'mesym'),
			sanitize_title('Education') => __('Education', 'mesym'),
			sanitize_title('Empowerment') => __('Empowerment', 'mesym'),
			sanitize_title('Energy') => __('Energy', 'mesym'),
			sanitize_title('Food') => __('Food', 'mesym'),
			sanitize_title('Population growth') => __('Population growth', 'mesym'),
			sanitize_title('Public awareness') => __('Public awareness', 'mesym'),
			sanitize_title('Social welfare') => __('Social welfare', 'mesym'),
			sanitize_title('Sustainability') => __('Sustainability', 'mesym'),
			sanitize_title('Transportation') => __('Transportation', 'mesym'),
			sanitize_title('Urban planning') => __('Urban planning', 'mesym'),
			sanitize_title('Waste') => __('Waste', 'mesym'),
			sanitize_title('Water') => __('Water', 'mesym'),
			sanitize_title('Other') => __('Other', 'mesym'),
		)
	);

	return $categories;
}

/**---------------------------------------------------------------------------------------------------------------
 * Embed Frame empty source
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_EmbedPreviewLayouts:get_frame_src', 'mesymtheme_embedemptysource');
function mesymtheme_embedemptysource($src) {

	return MESYM_ASSETS_URI.'/img/iframebg.jpg';
}