<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Misc\RequestUtils;

define('GD_CONSTANT_REPLICATETYPE_MULTIPLE', 'multiple');
define('GD_CONSTANT_REPLICATETYPE_SINGLE', 'single');

trait PoP_Module_Processor_InterceptablePageSectionsTrait
{
    public function getModuleInterceptUrls(array $module, array &$props)
    {
        $ret = parent::getModuleInterceptUrls($module, $props);

        $moduleOutputName = ModuleUtils::getModuleOutputName($module);

        // Intercept current page
        $url = RequestUtils::getCurrentUrl();
        $ret[$moduleOutputName] = $url;

        return $ret;
    }
    
    public function getPagesectionJsmethod(array $module, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($module, $props);

        $this->addJsmethod($ret, 'destroyPage', 'destroy-interceptor');

        return $ret;
    }
}
