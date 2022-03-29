<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public const MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES = 'forminput-linkcategories';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES:
                return GD_FormInput_LinkCategories::class;
        }
        
        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES:
                return 'linkcategories';
        }
        
        return parent::getDbobjectField($module);
    }
}



