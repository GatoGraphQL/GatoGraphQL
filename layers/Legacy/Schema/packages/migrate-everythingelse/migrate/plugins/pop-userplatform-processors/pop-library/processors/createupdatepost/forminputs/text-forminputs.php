<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_CUP_TITLE = 'forminput-cup-title';
    public final const COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK = 'forminput-link';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_CUP_TITLE,
            self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_TITLE:
                return TranslationAPIFacade::getInstance()->__('Title', 'poptheme-wassup');

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_TITLE:
            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_TITLE:
                return 'titleEdit';

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK:
                return 'contentEdit';

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return 'linkaccess';
        }
        
        return parent::getDbobjectField($component);
    }
}



