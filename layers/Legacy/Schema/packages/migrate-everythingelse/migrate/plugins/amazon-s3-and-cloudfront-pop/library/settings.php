<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Allow to hardcode the settings, so no need to input them as options in the DB
//-------------------------------------------------------------------------------------
\PoP\Root\App::getHookManager()->addFilter('aws_get_setting', 'wassupAwsGetSetting', 10, 2);
function wassupAwsGetSetting($setting, $key)
{

    // What bucket (ripess, ripess-dev)
    if ($key == 'bucket' && defined('AWSS3CF_BUCKET') && AWSS3CF_BUCKET) {
        return AWSS3CF_BUCKET;
    }

    // URL Configuration: CloudFront or Custom Domain
    elseif ($key == 'domain' && defined('AWSS3CF_DOMAIN') && AWSS3CF_DOMAIN) {
        return AWSS3CF_DOMAIN;
    }

    // For Cloudfront URL, which one is it
    elseif ($key == 'cloudfront' && defined('AWSS3CF_CLOUDFRONT') && AWSS3CF_CLOUDFRONT) {
        return AWSS3CF_CLOUDFRONT;
    }

    // The Object Prefix is the same same one as the uploads folder
    elseif ($key == 'object-prefix') {
        // Code taken from plugins/amazon-s3-and-cloudfront/classes/amazon-s3-and-cloudfront.php function get_default_object_prefix()
        $uploads = wp_upload_dir();
        $parts   = parse_url($uploads['baseurl']);
        $path    = ltrim($parts['path'], '/');
        return trailingslashit($path);

        // return UPLOADS.'/';
    }

    return $setting;
}
