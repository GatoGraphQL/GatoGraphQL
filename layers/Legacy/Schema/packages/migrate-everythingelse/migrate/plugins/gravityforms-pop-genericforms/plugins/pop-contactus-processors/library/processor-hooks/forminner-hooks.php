<?php

class PoP_ContactUs_Module_Processor_GFFormInnerHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_GFFormInners:init-props',
            $this->initModelProps(...),
            10,
            3
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_GFFormInners:layouts',
            $this->getLayoutSubcomponents(...),
            10,
            2
        );
    }

    public function getLayoutSubcomponents($layouts, array $component)
    {
        switch ($component[1]) {
            case PoP_ContactUs_Module_Processor_GFFormInners::COMPONENT_FORMINNER_CONTACTUS:
                $layouts[] = [GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::COMPONENT_GF_FORMINPUT_FORMID];
                break;
        }

        return $layouts;
    }

    public function initModelProps(array $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($component[1]) {
            case PoP_ContactUs_Module_Processor_GFFormInners::COMPONENT_FORMINNER_CONTACTUS:
                // Form ID
                $form_id = PoP_ContactUs_GFHelpers::getContactusFormId();
                $processor->setProp([GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::COMPONENT_GF_FORMINPUT_FORMID], $props, 'default-value'/*'selected-value'*/, $form_id);
                break;
        }
    }
}



