<?php

use PoP\ComponentModel\Component\Component;

class PoP_Share_Module_Processor_GFFormInnerHooks
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

    /**
     * @param Component[] $layouts
     * @return Component[]
     */
    public function getLayoutSubcomponents(array $layouts, \PoP\ComponentModel\Component\Component $component): array
    {
        switch ($component->name) {
            case PoP_Share_Module_Processor_GFFormInners::COMPONENT_FORMINNER_SHAREBYEMAIL:
                $layouts[] = [GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::COMPONENT_GF_FORMINPUT_FORMID];
                break;
        }

        return $layouts;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($component->name) {
            case PoP_Share_Module_Processor_GFFormInners::COMPONENT_FORMINNER_SHAREBYEMAIL:
                // Form ID
                $form_id = PoP_Share_GFHelpers::getSharebyemailFormId();
                $processor->setProp([GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::COMPONENT_GF_FORMINPUT_FORMID], $props, 'default-value'/*'selected-value'*/, $form_id);
                break;
        }
    }
}



