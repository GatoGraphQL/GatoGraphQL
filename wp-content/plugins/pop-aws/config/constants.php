<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * IDs Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// AWS
//--------------------------------------------------------
if (!defined('AWS_ACCESS_KEY_ID')) {
	define( 'AWS_ACCESS_KEY_ID', '');
}
if (!defined('AWS_SECRET_ACCESS_KEY')) {
	define( 'AWS_SECRET_ACCESS_KEY', '');
}
if (!defined('POP_AWS_REGION')) {
	define( 'POP_AWS_REGION', '');
}
if (!defined('POP_AWS_UPLOADSBUCKET')) {
	define( 'POP_AWS_UPLOADSBUCKET', '');
}

// CDN URLs
//--------------------------------------------------------
if (!defined('POP_AWS_CDN_ASSETS_URI')) {
	define ('POP_AWS_CDN_ASSETS_URI', false);
}
if (!defined('POP_AWS_CDN_UPLOADS_URI')) {
	define ('POP_AWS_CDN_UPLOADS_URI', false);
}