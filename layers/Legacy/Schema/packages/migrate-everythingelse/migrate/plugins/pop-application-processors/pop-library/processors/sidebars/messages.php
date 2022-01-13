<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public const MODULE_MESSAGE_NOCATEGORIES = 'message-nocategories';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MESSAGE_NOCATEGORIES],
        );
    }

    public function getMessage(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MESSAGE_NOCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('No Categories', 'poptheme-wassup');
        }

        return parent::getMessage($module);
    }
}



