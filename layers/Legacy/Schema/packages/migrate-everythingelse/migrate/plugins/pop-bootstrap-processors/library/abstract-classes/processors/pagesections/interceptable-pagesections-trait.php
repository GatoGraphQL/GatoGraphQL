<?php
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;

define('GD_CONSTANT_REPLICATETYPE_MULTIPLE', 'multiple');
define('GD_CONSTANT_REPLICATETYPE_SINGLE', 'single');

trait PoP_Module_Processor_InterceptablePageSectionsTrait
{
    public function getModuleInterceptUrls(array $component, array &$props)
    {
        $ret = parent::getModuleInterceptUrls($component, $props);

        $moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($component);

        // Intercept current page
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        $ret[$moduleOutputName] = $url;

        return $ret;
    }
    
    public function getPagesectionJsmethod(array $component, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($component, $props);

        $this->addJsmethod($ret, 'destroyPage', 'destroy-interceptor');

        return $ret;
    }
}
