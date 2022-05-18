<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_UserCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL = 'code-emailnotifications-networklabel';
    public final const MODULE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL = 'code-emailnotifications-subscribedtopicslabel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL],
            [self::class, self::MODULE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL],
        );
    }

    public function getCode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL:
            case self::MODULE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL:
                $titles = array(
                    self::MODULE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL => TranslationAPIFacade::getInstance()->__('A user on my network:', 'pop-coreprocessors'),
                    self::MODULE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL => TranslationAPIFacade::getInstance()->__('A topic I am subscribed to:', 'pop-coreprocessors'),
                );
                return sprintf(
                    '<h4>%s</h4>',
                    $titles[$module[1]]
                );
        }
    
        return parent::getCode($module, $props);
    }
}


