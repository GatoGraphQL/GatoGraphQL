<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_FORMINPUT_APPLIESTO = 'forminput-appliesto';
    public final const MODULE_FORMINPUT_CATEGORIES = 'forminput-categories';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_APPLIESTO],
            [self::class, self::MODULE_FORMINPUT_CATEGORIES],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup');

            case self::MODULE_FORMINPUT_APPLIESTO:
                return TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::MODULE_FORMINPUT_APPLIESTO:
                return GD_FormInput_AppliesTo::class;
        }
        
        return parent::getInputClass($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return 'topics';

            case self::MODULE_FORMINPUT_APPLIESTO:
                return 'appliesto';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



