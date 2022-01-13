<?php
use PoP\FileStore\Facades\FileRendererFacade;
use PoP\FileStore\Facades\FileStoreFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPWebPlatform_SPAResourceLoader_InstallationHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction('PoPWebPlatform_Installation:generateResources', array($this, 'generateResources'));
    }

    public function generateResources()
    {
        global $pop_sparesourceloader_configfile, $pop_sparesourceloader_resources_configfile/*, $pop_sparesourceloader_initialresources_configfile*/, $pop_sparesourceloader_natureformatcombinationresources_configfile;
        // $pop_sparesourceloader_configfile->generate();
        FileRendererFacade::getInstance()->renderAndSave($pop_sparesourceloader_configfile);
        // $pop_sparesourceloader_resources_configfile->generate();
        FileRendererFacade::getInstance()->renderAndSave($pop_sparesourceloader_resources_configfile);
        
        // Comment Leo 20/11/2017: since making the backgroundLoad execute in window.addEventListener('load', function() {,
        // we can just wait to load resources.js, so no need for initialresources.js anymore
        // $pop_sparesourceloader_initialresources_configfile->generate();
        // (new PoP_SPAResourceLoader_ConfigAddResources_FileRenderer())->renderAndSave($pop_sparesourceloader_initialresources_configfile);

        // $pop_sparesourceloader_natureformatcombinationresources_configfile->generate();
        $renderer = new PoP_SPAResourceLoader_ConfigNatureFormatCombinationResources_FileRenderer(FileStoreFacade::getInstance());
        $renderer->renderAndSave($pop_sparesourceloader_natureformatcombinationresources_configfile);
    }
}

/**
 * Initialization
 */
new PoPWebPlatform_SPAResourceLoader_InstallationHooks();
