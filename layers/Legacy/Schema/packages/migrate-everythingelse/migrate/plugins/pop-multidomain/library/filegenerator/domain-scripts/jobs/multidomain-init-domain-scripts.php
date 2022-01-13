<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_MultiDomain_InitDomainScripts_Config extends \PoP\FileStore\File\AbstractRenderableFileFragment
{
    public function getAssetsPath(): string
    {
        return dirname(__FILE__).'/assets/js/multidomain-domainscripts.js';
    }

    // public function getRenderer()
    // {
    //     global $pop_multidomain_initdomainscripts_filerenderer;
    //     return $pop_multidomain_initdomainscripts_filerenderer;
    // }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        // Domain
        $configuration['$domainScripts'] = HooksAPIFacade::getInstance()->applyFilters(
            'PoP_MultiDomain_InitDomainScripts_Config:domain-scripts',
            array()
        );

        return $configuration;
    }
}

