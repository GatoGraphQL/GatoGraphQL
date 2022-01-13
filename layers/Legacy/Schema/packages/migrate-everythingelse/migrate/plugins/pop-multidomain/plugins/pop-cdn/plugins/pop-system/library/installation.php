<?php
use PoP\FileStore\Facades\FileRendererFacade;

class PoP_MultiDomain_CDN_Installation
{
    public function __construct()
    {
        \PoP\Root\App::addAction('PoP:system-generate', array($this,'systemGenerate'));
    }

    public function systemGenerate()
    {
        global $pop_multidomain_initdomainscripts_configfile;
        // $pop_multidomain_initdomainscripts_configfile->generate();
        FileRendererFacade::getInstance()->renderAndSave($pop_multidomain_initdomainscripts_configfile);
    }
}

/**
 * Initialization
 */
new PoP_MultiDomain_CDN_Installation();
