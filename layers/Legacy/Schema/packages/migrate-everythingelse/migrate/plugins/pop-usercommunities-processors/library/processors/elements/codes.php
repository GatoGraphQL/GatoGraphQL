<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_Codes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_URE_CODE_MEMBERSLABEL = 'ure-code-memberslabel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_CODE_MEMBERSLABEL],
        );
    }

    public function getCode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_CODE_MEMBERSLABEL:
                return sprintf(
                    '<em>%s</em>',
                    TranslationAPIFacade::getInstance()->__('Members:', 'poptheme-wassup')
                );
        }
    
        return parent::getCode($component, $props);
    }
}


