<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_MultiDomain_ResourceLoader_FileReproduction_Config extends PoP_MultiDomain_ResourceLoader_FileReproductionBase
{
    public function getAssetsPath(): string
    {

        // return POP_MULTIDOMAINSPARESOURCELOADER_ASSETS_DIR.'/js/jobs/multidomain-resourceloader-config.js';
        return dirname(dirname(__FILE__)) .'/assets/js/jobs/multidomain-resourceloader-config.js';
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        // Domain
        $configuration['$resourceLoaderSources'] = \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_MultiDomain_ResourceLoader_FileReproduction_Config:resourceloader-config:sources',
            array()
        );

        return $configuration;
    }
}

