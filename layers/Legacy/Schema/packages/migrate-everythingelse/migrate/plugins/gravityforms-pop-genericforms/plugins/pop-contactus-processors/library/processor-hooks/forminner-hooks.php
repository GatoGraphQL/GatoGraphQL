<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ContactUs_Module_Processor_GFFormInnerHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction(
            'PoP_Module_Processor_GFFormInners:init-props',
            array($this, 'initModelProps'),
            10,
            3
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_GFFormInners:layouts',
            array($this, 'getLayoutSubmodules'),
            10,
            2
        );
    }

    public function getLayoutSubmodules($layouts, array $module)
    {
        switch ($module[1]) {
            case PoP_ContactUs_Module_Processor_GFFormInners::MODULE_FORMINNER_CONTACTUS:
                $layouts[] = [GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::MODULE_GF_FORMINPUT_FORMID];
                break;
        }

        return $layouts;
    }

    public function initModelProps(array $module, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($module[1]) {
            case PoP_ContactUs_Module_Processor_GFFormInners::MODULE_FORMINNER_CONTACTUS:
                // Form ID
                $form_id = PoP_ContactUs_GFHelpers::getContactusFormId();
                $processor->setProp([GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::MODULE_GF_FORMINPUT_FORMID], $props, 'default-value'/*'selected-value'*/, $form_id);
                break;
        }
    }
}



