<?php
use PoP\FileStore\File\AbstractFile;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;

class PoP_SPAResourceLoader_ConfigAddResources_FileRenderer extends \PoP\FileStore\Renderer\FileRenderer
{
    public function renderAndSave(AbstractFile $file): void
    {
        // Add the version param to the URL
        $vars = ApplicationState::getVars();
        $fileurl = GeneralUtils::addQueryArgs([
            'ver' => $vars['version'], 
        ], $file->getFileurl());

        // Insert the current file URL to the generated resources file
        foreach ($file->getFragments() as $fragment) {
            $fragment->setFileURL($fileurl);
        }

        parent::renderAndSave($file);
    }
}
