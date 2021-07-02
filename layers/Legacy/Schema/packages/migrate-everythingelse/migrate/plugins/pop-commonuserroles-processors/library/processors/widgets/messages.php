<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_URE_Custom_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public const MODULE_URE_MESSAGE_NODETAILS = 'ure-message-nodetails';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_MESSAGE_NODETAILS],
        );
    }

    public function getMessage(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_MESSAGE_NODETAILS:
                return TranslationAPIFacade::getInstance()->__('No details', 'poptheme-wassup');
        }

        return parent::getMessage($module);
    }
}



