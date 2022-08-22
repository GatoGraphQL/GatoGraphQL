<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_UserCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL = 'code-emailnotifications-networklabel';
    public final const COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL = 'code-emailnotifications-subscribedtopicslabel';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL,
            self::COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL,
        );
    }

    public function getCode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL:
            case self::COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL:
                $titles = array(
                    self::COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL => TranslationAPIFacade::getInstance()->__('A user on my network:', 'pop-coreprocessors'),
                    self::COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL => TranslationAPIFacade::getInstance()->__('A topic I am subscribed to:', 'pop-coreprocessors'),
                );
                return sprintf(
                    '<h4>%s</h4>',
                    $titles[$component->name]
                );
        }
    
        return parent::getCode($component, $props);
    }
}


