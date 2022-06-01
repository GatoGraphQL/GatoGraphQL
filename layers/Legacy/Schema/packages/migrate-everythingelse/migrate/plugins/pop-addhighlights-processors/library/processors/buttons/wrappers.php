<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class Wassup_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW = 'buttonwrapper-highlightview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW:
                $ret[] = [PoP_AddHighlights_Module_Processor_Buttons::class, PoP_AddHighlights_Module_Processor_Buttons::COMPONENT_BUTTON_HIGHLIGHTVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}



