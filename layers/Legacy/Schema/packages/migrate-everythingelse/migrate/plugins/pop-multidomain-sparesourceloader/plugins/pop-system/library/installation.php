<?php
use PoP\FileStore\Facades\FileRendererFacade;

class PoP_MultiDomainSPAResourceLoader_Installation
{
    public function __construct()
    {
        \PoP\Root\App::addAction('PoP:system-generate', array($this, 'systemGenerate'));
    }

    public function systemGenerate()
    {
        global $pop_multidomain_resourceloader_configfile;
        // $pop_multidomain_resourceloader_configfile->generate();
        FileRendererFacade::getInstance()->renderAndSave($pop_multidomain_resourceloader_configfile);
    }
}

/**
 * Initialization
 */
new PoP_MultiDomainSPAResourceLoader_Installation();
