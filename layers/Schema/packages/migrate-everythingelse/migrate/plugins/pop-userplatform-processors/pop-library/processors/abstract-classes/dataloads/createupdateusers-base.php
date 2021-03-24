<?php
use PoP\Translation\Facades\TranslationAPIFacade;

abstract class PoP_Module_Processor_CreateUpdateUserDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function initModelProps(array $module, array &$props): void
    {
        $submitting = TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-userplatform-processors');
        $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', $submitting);

        parent::initModelProps($module, $props);
    }
}
