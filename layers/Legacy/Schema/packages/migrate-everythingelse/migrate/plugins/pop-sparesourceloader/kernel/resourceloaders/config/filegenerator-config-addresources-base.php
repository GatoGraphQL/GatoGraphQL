<?php
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_SPAResourceLoader_ConfigAddResourcesFileBase extends PoP_SPAResourceLoader_ConfigFileBase
{

    // // Generate multiple config files (one for each combination of nature and format) instead of just one
    // public function generate()
    // {

    //     // Insert the current file URL to the generated resources file
    //     $renderer = $this->getRenderer();
    //     $renderer_filereproductions = $renderer->get();

    //     // Add the version param to the URL
    //         //     $vars = ApplicationState::getVars();
    //     $fileurl = GeneralUtils::addQueryArgs([
    //         'ver' => $vars['version'], 
    //     ], $this->getFileurl());
    //     foreach ($renderer_filereproductions as $filereproduction) {
    //         $filereproduction->setFileURL($fileurl);
    //     }

    //     parent::generate();
    // }
}
