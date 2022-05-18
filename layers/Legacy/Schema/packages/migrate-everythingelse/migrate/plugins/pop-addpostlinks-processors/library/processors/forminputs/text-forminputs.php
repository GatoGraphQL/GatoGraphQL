<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddPostLinks_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_ADDPOSTLINKS_FORMINPUT_LINK = 'forminput-postlink';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ADDPOSTLINKS_FORMINPUT_LINK],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUT_LINK:
                return TranslationAPIFacade::getInstance()->__('Embed external link', 'poptheme-wassup');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUT_LINK:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUT_LINK:
                return 'link';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



