<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public final const MODULE_URE_MESSAGE_NOCOMMUNITIES = 'ure-message-nocommunities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_MESSAGE_NOCOMMUNITIES],
        );
    }

    public function getMessage(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_URE_MESSAGE_NOCOMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('No Communities', 'ure-popprocessors');
        }

        return parent::getMessage($component);
    }
}



