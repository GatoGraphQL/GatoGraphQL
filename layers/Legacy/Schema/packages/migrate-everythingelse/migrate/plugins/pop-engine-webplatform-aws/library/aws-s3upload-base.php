<?php
use Aws\Common\Aws;

use PoP\ComponentModel\Misc\GeneralUtils;

class PoP_WebPlatformEngine_AWS_S3UploadBase extends PoP_AWS_S3UploadBase
{
    public function __construct()
    {

        // Register the AWS S3 domain in the Allowed Domains list
        \PoP\Root\App::addFilter(
            'pop_componentVariationmanager:allowed_domains',
            $this->getAllowedDomains(...)
        );
    }

    protected function getBucket()
    {
        return POP_AWS_UPLOADSBUCKET;
    }

    public function getAllowedDomains($domains)
    {
        $domains[] = GeneralUtils::getDomain($this->getDomain());
        return $domains;
    }

    protected function getCachecontrolMaxage()
    {

        // Cache-control max age: 1 month
        return 'max-age=2592000, must-revalidate';
    }
}
