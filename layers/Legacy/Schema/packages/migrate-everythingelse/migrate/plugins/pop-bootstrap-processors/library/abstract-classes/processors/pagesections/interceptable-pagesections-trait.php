<?php
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;

define('GD_CONSTANT_REPLICATETYPE_MULTIPLE', 'multiple');
define('GD_CONSTANT_REPLICATETYPE_SINGLE', 'single');

trait PoP_Module_Processor_InterceptablePageSectionsTrait
{
    public function getComponentInterceptURLs(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getComponentInterceptURLs($component, $props);

        $componentOutputName = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($component);

        // Intercept current page
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        $ret[$componentOutputName] = $url;

        return $ret;
    }
    
    public function getPagesectionJsmethod(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($component, $props);

        $this->addJsmethod($ret, 'destroyPage', 'destroy-interceptor');

        return $ret;
    }
}
