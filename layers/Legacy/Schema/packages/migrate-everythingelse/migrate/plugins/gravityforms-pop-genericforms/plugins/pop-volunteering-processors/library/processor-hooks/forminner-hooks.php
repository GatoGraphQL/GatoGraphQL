<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Volunteering_Module_Processor_GFFormInnerHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'PoP_Module_Processor_GFFormInners:init-props',
            array($this, 'initModelProps'),
            10,
            3
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_GFFormInners:layouts',
            array($this, 'getLayoutSubmodules'),
            10,
            2
        );
    }

    public function getLayoutSubmodules($layouts, array $module)
    {
        switch ($module[1]) {
            case PoP_Volunteering_Module_Processor_GFFormInners::MODULE_FORMINNER_VOLUNTEER:
                $layouts[] = [GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::MODULE_GF_FORMINPUT_FORMID];
                $layouts[] = [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_TARGETURL];
                $layouts[] = [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_POSTTITLE];
                break;
        }

        return $layouts;
    }

    public function initModelProps(array $module, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($module[1]) {
            case PoP_Volunteering_Module_Processor_GFFormInners::MODULE_FORMINNER_VOLUNTEER:
                // Form ID
                $form_id = PoP_Volunteering_GFHelpers::getVolunteerFormId();
                $processor->setProp([GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::MODULE_GF_FORMINPUT_FORMID], $props, 'default-value'/*'selected-value'*/, $form_id);
                break;
        }
    }
}



