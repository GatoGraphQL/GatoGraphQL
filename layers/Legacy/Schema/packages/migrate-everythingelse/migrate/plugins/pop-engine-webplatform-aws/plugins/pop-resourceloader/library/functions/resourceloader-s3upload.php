<?php

class PoP_WebPlatformEngine_AWS_Resourceloader_S3Upload extends PoP_WebPlatformEngine_AWS_S3UploadBase
{
    protected $files_to_upload;

    public function __construct()
    {
        parent::__construct();

        $this->files_to_upload = array();

        \PoP\Root\App::addAction(
            'PoP_ResourceLoader_FileGenerator_BundleFilesBase:generate-item',
            $this->enqueueForS3(...),
            10,
            4
        );

        \PoP\Root\App::addAction(
            'PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils:generated-bundlefiles',
            $this->uploadFilesToS3(...)
        );

        // Priority 100: execute on the late side
        \PoP\Root\App::addFilter(
            'PoP_ResourceLoader_ResourcesFileBase:base-url',
            $this->getResourcesBaseUrl(...),
            100,
            2
        );
    }

    public function handleBaseUrl($acrossThememodes)
    {

        // Allow PoP Cluster ResourceLoader AWS to take over file uploading
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformEngine_AWS_Resourceloader_S3Upload:handleBaseUrl',
            true,
            $acrossThememodes
        );
    }

    public function getResourcesBaseUrl($base_url, $acrossThememodes)
    {
        if ($this->handleBaseUrl($acrossThememodes)) {
            // Modify the file url domain to point to the Uploads S3 bucket instead
            $parts = parse_url($base_url);
            return $this->getDomain().$parts['path'];
        }

        return $base_url;
    }

    public function enqueueFiles($type, $subtype)
    {

        // Allow PoP Cluster ResourceLoader AWS to take over file uploading
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformEngine_AWS_Resourceloader_S3Upload:enqueueFiles',
            true,
            $type,
            $subtype
        );
    }

    public function enqueueForS3($file_path, $generated_referenced_files, $type, $subtype)
    {
        if ($this->enqueueFiles($type, $subtype)) {
            // Dynamic bundlefiles cannot be stored under pop-clusteruploads, so store the assets by subtype
            $this->files_to_upload = array_unique(
                array_merge(
                    $this->files_to_upload,
                    array(
                        $file_path,
                    ),
                    $generated_referenced_files
                )
            );
        }
    }

    protected function skipIfItExists()
    {
        return false;
    }

    public function uploadFilesToS3()
    {
        foreach ($this->files_to_upload as $file) {
            $this->uploadToS3($file);
        }
    }
}

/**
 * Initialization
 */
new PoP_WebPlatformEngine_AWS_Resourceloader_S3Upload();
