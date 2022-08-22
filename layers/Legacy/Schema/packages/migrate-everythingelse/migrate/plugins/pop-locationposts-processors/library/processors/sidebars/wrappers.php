<?php

class GD_Custom_EM_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES = 'layoutwrapper-locationpost-categories';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                $ret[] = [GD_Custom_EM_Module_Processor_Layouts::class, GD_Custom_EM_Module_Processor_Layouts::COMPONENT_LAYOUT_LOCATIONPOST_CATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionFailedSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                $ret[] = [GD_Custom_Module_Processor_WidgetMessages::class, GD_Custom_Module_Processor_WidgetMessages::COMPONENT_MESSAGE_NOCATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                return 'has-locationpostcategories';
        }

        return null;
    }
}



