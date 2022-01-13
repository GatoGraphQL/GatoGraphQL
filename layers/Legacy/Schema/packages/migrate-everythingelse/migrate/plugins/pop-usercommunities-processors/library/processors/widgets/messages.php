<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public const MODULE_URE_MESSAGE_NOCOMMUNITIES = 'ure-message-nocommunities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_MESSAGE_NOCOMMUNITIES],
        );
    }

    public function getMessage(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_MESSAGE_NOCOMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('No Communities', 'ure-popprocessors');
        }

        return parent::getMessage($module);
    }
}



