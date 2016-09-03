<?php

/**---------------------------------------------------------------------------------------------------------------
 * Config for DEV
 * ---------------------------------------------------------------------------------------------------------------*/

// Configuration for AWS for IAM user
// They might've been defined by plugin "Amazon Web Services" already
if (!defined('AWS_ACCESS_KEY_ID')) {

	// Fill here your AWS Credentials
	define( 'AWS_ACCESS_KEY_ID', false);
	define( 'AWS_SECRET_ACCESS_KEY', false);
}

// Fill here your AWS Region
define( 'POP_AWS_REGION', false);

// Target bucket: where to copy the userphoto when the user presses "save"
// Fill here your bucket name
define( 'POP_AWS_UPLOADSBUCKET', false);

// Your assets cloudfront URL
define ('POP_AWS_CDN_ASSETS_URI', false);

// Your uploads cloudfront URL
define ('POP_AWS_CDN_UPLOADS_URI', false);