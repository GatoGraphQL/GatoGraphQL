<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public final const COMPONENT_MESSAGE_NOREFERENCES = 'message-noreferences';
    public final const COMPONENT_MESSAGE_NOCONTACT = 'message-nocontact';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MESSAGE_NOREFERENCES,
            self::COMPONENT_MESSAGE_NOCONTACT,
        );
    }

    public function getMessage(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MESSAGE_NOREFERENCES:
                return TranslationAPIFacade::getInstance()->__('Nothing here', 'pop-coreprocessors');

            case self::COMPONENT_MESSAGE_NOCONTACT:
                return TranslationAPIFacade::getInstance()->__('No contact details', 'pop-coreprocessors');
        }

        return parent::getMessage($component);
    }
}



