<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public final const COMPONENT_MESSAGE_NOCATEGORIES = 'message-nocategories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MESSAGE_NOCATEGORIES],
        );
    }

    public function getMessage(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MESSAGE_NOCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('No Categories', 'poptheme-wassup');
        }

        return parent::getMessage($component);
    }
}



