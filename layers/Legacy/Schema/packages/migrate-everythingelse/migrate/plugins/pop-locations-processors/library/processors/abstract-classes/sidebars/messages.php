<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public final const COMPONENT_EM_MESSAGE_NOLOCATION = 'em-message-nolocation';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_MESSAGE_NOLOCATION,
        );
    }

    public function getMessage(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_MESSAGE_NOLOCATION:
                return TranslationAPIFacade::getInstance()->__('No location', 'em-popprocessors');
        }

        return parent::getMessage($component);
    }
}



