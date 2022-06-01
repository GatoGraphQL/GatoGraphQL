<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_WidgetMessages extends PoP_Module_Processor_WidgetMessagesBase
{
    public final const COMPONENT_URE_MESSAGE_NOCOMMUNITIES = 'ure-message-nocommunities';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_MESSAGE_NOCOMMUNITIES,
        );
    }

    public function getMessage(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_MESSAGE_NOCOMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('No Communities', 'ure-popprocessors');
        }

        return parent::getMessage($component);
    }
}



