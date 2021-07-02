<?php

abstract class PoP_SPAResourceLoader_FileReproduction_AddResourcesConfigBase extends PoP_SPAResourceLoader_FileReproduction_ResourcesConfigBase
{
    protected $fileurl;

    public function getAssetsPath(): string
    {
        return POP_SPARESOURCELOADER_ASSETS_DIR.'/js/jobs/resourceloader-config-addresources.js';
    }

    public function setFileURL($fileurl)
    {
        return $this->fileurl = $fileurl;
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        // Add the fileurl
        $configuration['$fileurl'] = $this->fileurl;

        return $configuration;
    }
}
