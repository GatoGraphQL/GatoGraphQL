<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateUserTextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const MODULE_FORMINPUT_CUU_DESCRIPTION = 'forminput-cuu-description';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUU_DESCRIPTION],
        );
    }

    public function getRows(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUU_DESCRIPTION:
                return 10;
        }

        return parent::getRows($componentVariation, $props);
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUU_DESCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Description', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUU_DESCRIPTION:
                return 'description';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



