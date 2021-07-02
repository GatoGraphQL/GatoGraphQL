<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_WebPlatformEngine_AWS_InitializeData_S3Upload extends PoP_WebPlatformEngine_AWS_S3UploadBase
{
    public function __construct()
    {
        parent::__construct();

        HooksAPIFacade::getInstance()->addAction(
            '\PoP\ComponentModel\Engine:optimizeEncodedData:file_stored',
            array($this, 'maybeUploadToS3'),
            10,
            3
        );

        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_RuntimeContentManager:cache-baseurl',
            array($this, 'modifyFileurlDomain')
        );
    }

    public function modifyFileurlDomain($baseurl)
    {

        // Modify the file url domain to point to the Uploads S3 bucket instead
        $parts = parse_url($baseurl);
        return $this->getDomain().$parts['path'];
    }

    public function maybeUploadToS3(array $module, $property_path, $type/*, $value_js*/)
    {
        global $pop_module_runtimecontentmanager;
        if ($file = $pop_module_runtimecontentmanager->getFileWithModelInstanceFilename($type)) {
            $this->uploadToS3($file);
        }
    }
}

/**
 * Initialization
 */
new PoP_WebPlatformEngine_AWS_InitializeData_S3Upload();
