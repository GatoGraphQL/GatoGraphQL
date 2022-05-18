<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_UserCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL = 'code-emailnotifications-networklabel';
    public final const COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL = 'code-emailnotifications-subscribedtopicslabel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL],
            [self::class, self::COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL],
        );
    }

    public function getCode(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL:
            case self::COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL:
                $titles = array(
                    self::COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL => TranslationAPIFacade::getInstance()->__('A user on my network:', 'pop-coreprocessors'),
                    self::COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL => TranslationAPIFacade::getInstance()->__('A topic I am subscribed to:', 'pop-coreprocessors'),
                );
                return sprintf(
                    '<h4>%s</h4>',
                    $titles[$component[1]]
                );
        }
    
        return parent::getCode($component, $props);
    }
}


