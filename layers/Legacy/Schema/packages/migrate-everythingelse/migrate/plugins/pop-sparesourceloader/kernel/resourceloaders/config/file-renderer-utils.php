<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\FileStore\File\AbstractFile;

class PoP_SPAResourceLoader_ConfigAddResources_FileRenderer extends \PoP\FileStore\Renderer\FileRenderer
{
    public function renderAndSave(AbstractFile $file): void
    {
        // Add the version param to the URL
        $fileurl = GeneralUtils::addQueryArgs([
            'ver' => ApplicationInfoFacade::getInstance()->getVersion(), 
        ], $file->getFileurl());

        // Insert the current file URL to the generated resources file
        foreach ($file->getFragments() as $fragment) {
            $fragment->setFileURL($fileurl);
        }

        parent::renderAndSave($file);
    }
}
