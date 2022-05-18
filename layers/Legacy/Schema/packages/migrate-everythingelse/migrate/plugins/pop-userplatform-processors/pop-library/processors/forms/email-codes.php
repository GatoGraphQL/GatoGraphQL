<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_CODE_EMAILNOTIFICATIONS_LABEL = 'code-emailnotifications-label';
    public final const MODULE_CODE_EMAILNOTIFICATIONS_GENERALLABEL = 'code-emailnotifications-generallabel';
    public final const MODULE_CODE_EMAILDIGESTS_LABEL = 'code-dailyemaildigestslabel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_EMAILNOTIFICATIONS_LABEL],
            [self::class, self::MODULE_CODE_EMAILNOTIFICATIONS_GENERALLABEL],
            [self::class, self::MODULE_CODE_EMAILDIGESTS_LABEL],
        );
    }

    public function getCode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CODE_EMAILNOTIFICATIONS_LABEL:
            case self::MODULE_CODE_EMAILDIGESTS_LABEL:
                $titles = array(
                    self::MODULE_CODE_EMAILNOTIFICATIONS_LABEL => TranslationAPIFacade::getInstance()->__('Email notifications', 'pop-coreprocessors'),
                    self::MODULE_CODE_EMAILDIGESTS_LABEL => TranslationAPIFacade::getInstance()->__('Email digests', 'pop-coreprocessors'),
                );
                return sprintf(
                    '<h3>%s</h3>',
                    $titles[$module[1]]
                );

            case self::MODULE_CODE_EMAILNOTIFICATIONS_GENERALLABEL:
                $titles = array(
                    self::MODULE_CODE_EMAILNOTIFICATIONS_GENERALLABEL => TranslationAPIFacade::getInstance()->__('General:', 'pop-coreprocessors'),
                );
                return sprintf(
                    '<h4>%s</h4>',
                    $titles[$module[1]]
                );
        }
    
        return parent::getCode($module, $props);
    }
}


