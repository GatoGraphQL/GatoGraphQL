<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class Wassup_Module_Processor_MultipleComponentLayoutWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION = 'multicomponentwrapper-userhighlightpostinteraction';
    public final const COMPONENT_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION = 'multicomponentwrapper-userpostinteraction';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION],
            [self::class, self::COMPONENT_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION],
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_MultipleComponentLayouts::class, Wassup_Module_Processor_MultipleComponentLayouts::COMPONENT_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION];
                break;

            case self::COMPONENT_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_MultipleComponentLayouts::class, Wassup_Module_Processor_MultipleComponentLayouts::COMPONENT_MULTICOMPONENT_USERPOSTINTERACTION];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
            case self::COMPONENT_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}



