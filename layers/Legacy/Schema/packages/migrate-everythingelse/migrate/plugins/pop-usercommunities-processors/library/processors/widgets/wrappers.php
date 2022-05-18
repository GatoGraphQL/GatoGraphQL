<?php

class GD_URE_Module_Processor_SidebarComponentsWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_URE_WIDGETWRAPPER_COMMUNITIES = 'ure-widgetwrapper-communities';
    public final const COMPONENT_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES = 'ure-widgetcompactwrapper-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_WIDGETWRAPPER_COMMUNITIES],
            [self::class, self::COMPONENT_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_WIDGETWRAPPER_COMMUNITIES:
                $ret[] = [GD_URE_Module_Processor_Widgets::class, GD_URE_Module_Processor_Widgets::COMPONENT_URE_WIDGET_COMMUNITIES];
                break;

            case self::COMPONENT_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES:
                $ret[] = [GD_URE_Module_Processor_Widgets::class, GD_URE_Module_Processor_Widgets::COMPONENT_URE_WIDGETCOMPACT_COMMUNITIES];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_WIDGETWRAPPER_COMMUNITIES:
            case self::COMPONENT_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES:
                return 'hasActiveCommunities';
        }

        return null;
    }
}



