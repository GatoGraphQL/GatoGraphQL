<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddPostLinks_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_ADDPOSTLINKS_FORMINPUT_LINK = 'forminput-postlink';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_ADDPOSTLINKS_FORMINPUT_LINK],
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_ADDPOSTLINKS_FORMINPUT_LINK:
                return TranslationAPIFacade::getInstance()->__('Embed external link', 'poptheme-wassup');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_ADDPOSTLINKS_FORMINPUT_LINK:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_ADDPOSTLINKS_FORMINPUT_LINK:
                return 'link';
        }
        
        return parent::getDbobjectField($component);
    }
}



