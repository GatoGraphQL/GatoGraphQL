<?php

use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_FormDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    
    // Comment Leo 12/01/2017: make it runtime instead of static, since it needs to validate if the user is logged in
    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestHeaddatasetmoduleDataProperties($module, $props);
    
        // Check if needed to validate Captcha
        if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
            if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                if ($this->validateCaptcha($module, $props)) {
                    if (!(PoP_FormUtils::useLoggedinuserData() && \PoP\Root\App::getState('is-user-logged-in'))) {
                        $ret[GD_DATALOAD_QUERYHANDLERPROPERTY_FORM_VALIDATECAPTCHA] = true;
                    }
                }
            }
        }
        
        return $ret;
    }

    protected function validateCaptcha(array $module, array &$props)
    {
        return false;
    }
}
