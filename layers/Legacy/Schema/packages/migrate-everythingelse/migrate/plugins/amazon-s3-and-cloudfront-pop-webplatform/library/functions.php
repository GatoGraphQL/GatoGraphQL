<?php
use PoP\ComponentModel\Misc\GeneralUtils;

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// Register the AWS S3 domain in the Allowed Domains list
\PoP\Root\App::addFilter('pop_modulemanager:allowed_domains', 'popAwss3Allowedurl');
function popAwss3Allowedurl($allowed_domains)
{

    // Comment Leo 29/12/2017: getting the region like below doesn't work, so get it from outside then
    // $region = $as3cf->get_setting('region');
    if ($region = \PoP\Root\App::applyFilters('popAwss3Allowedurl:region', '')) {
        global $as3cf;
        if ($as3cf) {
            // Copied from plugins/amazon-s3-and-cloudfront/classes/amazon-s3-and-cloudfront.php function get_s3_url_domain
            // Force https
            $scheme = $as3cf->get_s3_url_scheme(true);
            $bucket = $as3cf->get_setting('bucket');

            $domain = $as3cf->get_s3_url_domain($bucket, $region);
            $domain = $scheme . '://' . $domain;

            // We need to do getDomain because the S3 URL Domain may be domain/bucket (if doing AWSS3CF_DOMAIN = 'path')
            $allowed_domains[] = GeneralUtils::getDomain($domain);
        }
    }

    return $allowed_domains;
}
