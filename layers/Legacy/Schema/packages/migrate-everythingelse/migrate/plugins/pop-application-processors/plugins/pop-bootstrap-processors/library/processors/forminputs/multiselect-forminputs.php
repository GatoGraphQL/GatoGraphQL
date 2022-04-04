<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_FORMINPUT_APPLIESTO = 'forminput-appliesto';
    public final const MODULE_FORMINPUT_CATEGORIES = 'forminput-categories';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_APPLIESTO],
            [self::class, self::MODULE_FORMINPUT_CATEGORIES],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup');

            case self::MODULE_FORMINPUT_APPLIESTO:
                return TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::MODULE_FORMINPUT_APPLIESTO:
                return GD_FormInput_AppliesTo::class;
        }
        
        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CATEGORIES:
                return 'topics';

            case self::MODULE_FORMINPUT_APPLIESTO:
                return 'appliesto';
        }
        
        return parent::getDbobjectField($module);
    }
}



