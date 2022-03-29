<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddPostLinks_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public const MODULE_ADDPOSTLINKS_FORMINPUT_LINK = 'forminput-postlink';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ADDPOSTLINKS_FORMINPUT_LINK],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUT_LINK:
                return TranslationAPIFacade::getInstance()->__('Embed external link', 'poptheme-wassup');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUT_LINK:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUT_LINK:
                return 'link';
        }
        
        return parent::getDbobjectField($module);
    }
}



