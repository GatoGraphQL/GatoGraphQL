<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CreateUpdateUserDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function initModelProps(array $component, array &$props): void
    {
        $submitting = TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-userplatform-processors');
        $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', $submitting);

        parent::initModelProps($component, $props);
    }
}
