<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Application_Module_Processor_UserCheckpointMessageLayouts extends PoP_Module_Processor_CheckpointMessageLayoutsBase
{
    public const MODULE_LAYOUT_CHECKPOINTMESSAGE_DOMAIN = 'layout-checkpointmessage-domain';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_DOMAIN],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_DOMAIN:
                $ret['domainempty'] = TranslationAPIFacade::getInstance()->__('The domain is empty.', 'pop-coreprocessors');
                $ret['domainnotvalid'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Domain <strong>%s</strong> is not valid.', 'pop-coreprocessors'),
                    PoP_Application_Utils::getRequestDomain()
                );
                break;
        }

        return $ret;
    }
}



