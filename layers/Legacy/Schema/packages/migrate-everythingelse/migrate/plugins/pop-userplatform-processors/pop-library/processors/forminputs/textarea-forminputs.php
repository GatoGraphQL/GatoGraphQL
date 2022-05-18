<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateUserTextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const MODULE_FORMINPUT_CUU_DESCRIPTION = 'forminput-cuu-description';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_CUU_DESCRIPTION],
        );
    }

    public function getRows(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CUU_DESCRIPTION:
                return 10;
        }

        return parent::getRows($component, $props);
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CUU_DESCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Description', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CUU_DESCRIPTION:
                return 'description';
        }
        
        return parent::getDbobjectField($component);
    }
}



