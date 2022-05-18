<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES = 'forminput-linkcategories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES:
                return GD_FormInput_LinkCategories::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES:
                return 'linkcategories';
        }
        
        return parent::getDbobjectField($component);
    }
}



