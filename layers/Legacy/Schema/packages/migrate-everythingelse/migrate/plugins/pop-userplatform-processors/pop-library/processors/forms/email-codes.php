<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_CODE_EMAILNOTIFICATIONS_LABEL = 'code-emailnotifications-label';
    public final const COMPONENT_CODE_EMAILNOTIFICATIONS_GENERALLABEL = 'code-emailnotifications-generallabel';
    public final const COMPONENT_CODE_EMAILDIGESTS_LABEL = 'code-dailyemaildigestslabel';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CODE_EMAILNOTIFICATIONS_LABEL,
            self::COMPONENT_CODE_EMAILNOTIFICATIONS_GENERALLABEL,
            self::COMPONENT_CODE_EMAILDIGESTS_LABEL,
        );
    }

    public function getCode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CODE_EMAILNOTIFICATIONS_LABEL:
            case self::COMPONENT_CODE_EMAILDIGESTS_LABEL:
                $titles = array(
                    self::COMPONENT_CODE_EMAILNOTIFICATIONS_LABEL => TranslationAPIFacade::getInstance()->__('Email notifications', 'pop-coreprocessors'),
                    self::COMPONENT_CODE_EMAILDIGESTS_LABEL => TranslationAPIFacade::getInstance()->__('Email digests', 'pop-coreprocessors'),
                );
                return sprintf(
                    '<h3>%s</h3>',
                    $titles[$component->name]
                );

            case self::COMPONENT_CODE_EMAILNOTIFICATIONS_GENERALLABEL:
                $titles = array(
                    self::COMPONENT_CODE_EMAILNOTIFICATIONS_GENERALLABEL => TranslationAPIFacade::getInstance()->__('General:', 'pop-coreprocessors'),
                );
                return sprintf(
                    '<h4>%s</h4>',
                    $titles[$component->name]
                );
        }
    
        return parent::getCode($component, $props);
    }
}


