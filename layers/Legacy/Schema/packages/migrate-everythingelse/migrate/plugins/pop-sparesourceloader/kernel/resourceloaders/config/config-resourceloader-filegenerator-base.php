<?php

use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_SPAResourceLoader_ConfigFileBase extends \PoP\FileStore\File\AbstractAccessibleRenderableFile
{
    protected function getBaseDir()
    {
        return POP_SPARESOURCELOADER_CONTENT_DIR;
    }
    protected function getBaseUrl()
    {
        return POP_SPARESOURCELOADER_CONTENT_URL;
    }

    protected function getFolderSubpath()
    {

        // We must create different mapping files depending on if we're adding the CDN resources inside the bundles or not
        $subfolder = PoP_ResourceLoader_ServerUtils::bundleExternalFiles() ? 'global' : 'local';
        if (defined('POP_THEME_INITIALIZED')) {
            return '/'.\PoP\Root\App::getState('theme').'/'.\PoP\Root\App::getState('thememode').'/'.$subfolder;
        }

        return $subfolder;
    }

    public function getDir(): string
    {
        return $this->getBaseDir().$this->getFolderSubpath();
    }
    public function getUrl(): string
    {
        return $this->getBaseUrl().$this->getFolderSubpath();
    }
}
