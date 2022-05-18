<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_FORMINPUT_APPLIESTO = 'forminput-appliesto';
    public final const MODULE_FORMINPUT_CATEGORIES = 'forminput-categories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_APPLIESTO],
            [self::class, self::MODULE_FORMINPUT_CATEGORIES],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup');

            case self::MODULE_FORMINPUT_APPLIESTO:
                return TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::MODULE_FORMINPUT_APPLIESTO:
                return GD_FormInput_AppliesTo::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return 'topics';

            case self::MODULE_FORMINPUT_APPLIESTO:
                return 'appliesto';
        }
        
        return parent::getDbobjectField($component);
    }
}



