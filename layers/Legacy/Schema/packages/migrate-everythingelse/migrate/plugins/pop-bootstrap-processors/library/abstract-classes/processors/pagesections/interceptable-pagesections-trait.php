<?php
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;

define('GD_CONSTANT_REPLICATETYPE_MULTIPLE', 'multiple');
define('GD_CONSTANT_REPLICATETYPE_SINGLE', 'single');

trait PoP_Module_Processor_InterceptablePageSectionsTrait
{
    public function getModuleInterceptUrls(array $componentVariation, array &$props)
    {
        $ret = parent::getModuleInterceptUrls($componentVariation, $props);

        $moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($componentVariation);

        // Intercept current page
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        $ret[$moduleOutputName] = $url;

        return $ret;
    }
    
    public function getPagesectionJsmethod(array $componentVariation, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($componentVariation, $props);

        $this->addJsmethod($ret, 'destroyPage', 'destroy-interceptor');

        return $ret;
    }
}
