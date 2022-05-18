<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Custom_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public final const MODULE_URE_MESSAGE_NODETAILS = 'ure-message-nodetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_MESSAGE_NODETAILS],
        );
    }

    public function getMessage(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_URE_MESSAGE_NODETAILS:
                return TranslationAPIFacade::getInstance()->__('No details', 'poptheme-wassup');
        }

        return parent::getMessage($component);
    }
}



