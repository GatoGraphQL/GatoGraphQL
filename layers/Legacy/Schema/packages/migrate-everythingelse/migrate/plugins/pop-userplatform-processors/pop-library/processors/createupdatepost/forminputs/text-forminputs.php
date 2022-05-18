<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_CUP_TITLE = 'forminput-cup-title';
    public final const MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK = 'forminput-link';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_TITLE],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUP_TITLE:
                return TranslationAPIFacade::getInstance()->__('Title', 'poptheme-wassup');

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUP_TITLE:
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUP_TITLE:
                return 'titleEdit';

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return 'contentEdit';

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return 'linkaccess';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



