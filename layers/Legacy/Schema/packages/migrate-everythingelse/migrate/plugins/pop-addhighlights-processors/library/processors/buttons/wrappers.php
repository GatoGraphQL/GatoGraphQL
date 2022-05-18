<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class Wassup_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW = 'buttonwrapper-highlightview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW:
                $ret[] = [PoP_AddHighlights_Module_Processor_Buttons::class, PoP_AddHighlights_Module_Processor_Buttons::COMPONENT_BUTTON_HIGHLIGHTVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}



