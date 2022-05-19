<?php

class GD_Custom_EM_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES = 'layoutwrapper-locationpost-categories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES],
        );
    }

    public function getConditionSucceededSubcomponents(array $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                $ret[] = [GD_Custom_EM_Module_Processor_Layouts::class, GD_Custom_EM_Module_Processor_Layouts::COMPONENT_LAYOUT_LOCATIONPOST_CATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubcomponents(array $component)
    {
        $ret = parent::getConditionFailedSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                $ret[] = [GD_Custom_Module_Processor_WidgetMessages::class, GD_Custom_Module_Processor_WidgetMessages::COMPONENT_MESSAGE_NOCATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                return 'has-locationpostcategories';
        }

        return null;
    }
}



