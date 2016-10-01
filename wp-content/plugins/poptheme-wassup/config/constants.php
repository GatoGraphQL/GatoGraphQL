<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * IDs Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Homepage/Tag content
//--------------------------------------------------------
if (!defined('POPTHEME_WASSUP_PAGEPLACEHOLDER_HOME')) {

	// Define the Home as the page hosting 'All Content'
	define ('POPTHEME_WASSUP_PAGEPLACEHOLDER_HOME', POP_WPAPI_PAGE_ALLCONTENT);
}
if (!defined('POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG')) {

	// Define the Tag as the page hosting 'All Content'
	// define ('POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG', POP_WPAPI_PAGE_ALLCONTENT);
	define ('POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG', POP_COREPROCESSORS_PAGE_MAIN);
}

// Placeholders for images
//--------------------------------------------------------
define ('POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEHIGHLIGHTPOST', false);
define ('POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEWEBPOSTPOST', false);

// Categories
//--------------------------------------------------------
define ('POPTHEME_WASSUP_CAT_WEBPOSTLINKS', false);
define ('POPTHEME_WASSUP_CAT_HIGHLIGHTS', false);
define ('POPTHEME_WASSUP_CAT_WEBPOSTS', false);

// Content
//--------------------------------------------------------
define ('POPTHEME_WASSUP_PAGE_ADDCONTENT', false);
define ('POPTHEME_WASSUP_PAGE_WEBPOSTLINKS', false);
define ('POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS', false);
define ('POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK', false);
define ('POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK', false);
define ('POPTHEME_WASSUP_PAGE_WEBPOSTS', false);
define ('POPTHEME_WASSUP_PAGE_MYWEBPOSTS', false);
define ('POPTHEME_WASSUP_PAGE_ADDWEBPOST', false);
define ('POPTHEME_WASSUP_PAGE_EDITWEBPOST', false);
define ('POPTHEME_WASSUP_PAGE_HIGHLIGHTS', false);
define ('POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS', false);
define ('POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT', false);
define ('POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT', false);

// About/FAQs
//--------------------------------------------------------
define ('POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ', false);
define ('POPTHEME_WASSUP_PAGE_ACCOUNTFAQ', false);
