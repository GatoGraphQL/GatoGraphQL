<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public final const MODULE_MESSAGE_NOREFERENCES = 'message-noreferences';
    public final const MODULE_MESSAGE_NOCONTACT = 'message-nocontact';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MESSAGE_NOREFERENCES],
            [self::class, self::MODULE_MESSAGE_NOCONTACT],
        );
    }

    public function getMessage(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MESSAGE_NOREFERENCES:
                return TranslationAPIFacade::getInstance()->__('Nothing here', 'pop-coreprocessors');

            case self::MODULE_MESSAGE_NOCONTACT:
                return TranslationAPIFacade::getInstance()->__('No contact details', 'pop-coreprocessors');
        }

        return parent::getMessage($module);
    }
}



