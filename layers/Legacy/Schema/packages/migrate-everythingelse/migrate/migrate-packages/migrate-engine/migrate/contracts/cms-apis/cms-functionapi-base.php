<?php
namespace PoP\Engine;

use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

abstract class FunctionAPI_Base implements FunctionAPI
{
    public function __construct()
    {
        FunctionAPIFactory::setInstance($this);
    }
    public function getVersion()
    {
    	return '';
    }

    public function getHost(): string
    {
        $cmsService = CMSServiceFacade::getInstance();
        return removeScheme($cmsService->getHomeURL());
    }
}
