<?php
use Aws\Common\Aws;

use PoP\Root\Facades\Hooks\HooksAPIFacade;

// use Aws\S3\Exception\S3Exception;

class PoP_WebPlatformEngine_AWS_CDNHooks
{
    public function __construct()
    {

        // Priority: after same function in PoP_WebPlatformEngine_AWS_InitializeData
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_AWS_S3UploadBase:domain',
            array($this, 'getDomain'),
            100,
            2
        );
    }

    public function getDomain($domain, $bucket)
    {

        // Use the CDN domain instead of the S3 Bucket for the Uploads
        if ($bucket == POP_AWS_UPLOADSBUCKET && defined('POP_CDNFOUNDATION_CDN_UPLOADS_URI') && POP_CDNFOUNDATION_CDN_UPLOADS_URI) {
            return POP_CDNFOUNDATION_CDN_UPLOADS_URI;
        }

        return $domain;
    }
}

/**
 * Initialization
 */
new PoP_WebPlatformEngine_AWS_CDNHooks();
