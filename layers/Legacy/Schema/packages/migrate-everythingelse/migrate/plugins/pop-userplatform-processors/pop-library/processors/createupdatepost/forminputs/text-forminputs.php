<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public const MODULE_FORMINPUT_CUP_TITLE = 'forminput-cup-title';
    public const MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK = 'forminput-link';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_TITLE],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_TITLE:
                return TranslationAPIFacade::getInstance()->__('Title', 'poptheme-wassup');

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_TITLE:
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_TITLE:
                return 'titleEdit';

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return 'contentEdit';

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return 'linkaccess';
        }
        
        return parent::getDbobjectField($module);
    }
}



