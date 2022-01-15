<?php

use PoP\ComponentModel\Misc\RequestUtils;
use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class PoP_CoreProcessors_FileReproduction_UserLoggedInStyles extends PoP_Engine_CSSFileReproductionBase
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
    //     global $popcore_userloggedinstyles_filerenderer;
    //     return $popcore_userloggedinstyles_filerenderer;
    // }

    public function getAssetsPath(): string
    {
        return dirname(__FILE__).'/assets/css/user-loggedin.css';
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        $configuration['{{$domainId}}'] = RequestUtils::getDomainId($this->getDomain());

        return $configuration;
    }
}
