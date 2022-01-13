<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;

class PoP_CDN_FileReproduction_ThumbprintsConfig extends PoP_CDN_FileReproduction
{
    public function getAssetsPath(): string
    {
        return POP_CDN_ASSETS_DIR.'/js/jobs/cdn-config.js';
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();
        
        $cmsService = CMSServiceFacade::getInstance();
        $configuration['{{$domain}}'] = $cmsService->getSiteURL();
        $configuration['{{$cdnDomain}}'] = '';
        $configuration['{{$thumbprints}}'] = $configuration['{{$criteria_rejected}}'] = $configuration['{{$criteria_thumbprints}}'] = array();
        if (POP_CDNFOUNDATION_CDN_CONTENT_URI) {
            global $pop_cdn_thumbprint_manager;
            $thumbprints = $pop_cdn_thumbprint_manager->getThumbprints();
            $configuration['{{$cdnDomain}}'] = POP_CDNFOUNDATION_CDN_CONTENT_URI;
            $configuration['{{$thumbprints}}'] = $thumbprints;
            $configuration['{{$criteria_rejected}}'] = $this->getRejectedCriteriaitems();
            $configuration['{{$criteria_thumbprints}}'] = array();
            foreach ($thumbprints as $thumbprint) {
                $configuration['{{$criteria_thumbprints}}'][$thumbprint] = $this->getThumbprintsCriteriaitems($thumbprint);
            }
        }

        return $configuration;
    }

    protected function getRejectedCriteriaitems()
    {
        return array(
            'startsWith' => array(
                'full' => \PoP\Root\App::getHookManager()->applyFilters(
                    'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:startsWith:full',
                    array()
                ),
                'partial' => \PoP\Root\App::getHookManager()->applyFilters(
                    'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:startsWith:partial',
                    array()
                ),
            ),
            // Array of Arrays: elem[0] = URL param, elem[1] = value
            'hasParamValues' => \PoP\Root\App::getHookManager()->applyFilters(
                'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:hasParamValues',
                array()
            ),
            // Array of Arrays: elem[0] = URL param, elem[1] = value
            'noParamValues' => \PoP\Root\App::getHookManager()->applyFilters(
                'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:noParamValues',
                array()
            ),
            'isHome' => \PoP\Root\App::getHookManager()->applyFilters(
                'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:isHome',
                false
            )
        );
    }

    protected function getThumbprintsCriteriaitems($thumbprint)
    {
        return array(
            'startsWith' => array(
                'full' => \PoP\Root\App::getHookManager()->applyFilters(
                    'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:full',
                    array(),
                    $thumbprint
                ),
                'partial' => \PoP\Root\App::getHookManager()->applyFilters(
                    'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
                    array(),
                    $thumbprint
                ),
            ),
            'hasParamValues' => \PoP\Root\App::getHookManager()->applyFilters(
                'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:hasParamValues',
                array(),
                $thumbprint
            ),
            'noParamValues' => \PoP\Root\App::getHookManager()->applyFilters(
                'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:noParamValues',
                array(),
                $thumbprint
            ),
            'isHome' => \PoP\Root\App::getHookManager()->applyFilters(
                'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:isHome',
                false,
                $thumbprint
            )
        );
    }
}

