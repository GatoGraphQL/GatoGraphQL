<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CreateUpdateUserDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $submitting = TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-userplatform-processors');
        $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', $submitting);

        parent::initModelProps($component, $props);
    }
}
