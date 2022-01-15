<?php

use PoP\ComponentModel\Misc\RequestUtils;
use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class PoPTheme_Wassup_Multidomain_FileReproduction_Styles extends PoP_Engine_CSSFileReproductionBase
{
    protected $domain;

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getDomain()
    {
        $cmsService = CMSServiceFacade::getInstance();
        return $this->domain ?? $cmsService->getSiteURL();
    }

    // public function getRenderer()
    // {
    //     global $popthemewassup_multidomainstyles_filerenderer;
    //     return $popthemewassup_multidomainstyles_filerenderer;
    // }

    public function getAssetsPath(): string
    {
        return dirname(__FILE__).'/assets/css/multidomain.css';
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        $domain = $this->getDomain();
        $domain_bgcolors = PoPTheme_Wassup_MultiDomain_Utils::getMultidomainBgcolors();
        $configuration['{{$domainId}}'] = RequestUtils::getDomainId($domain);
        $configuration['{{$backgroundColor}}'] = $domain_bgcolors[$domain];

        return $configuration;
    }
}
