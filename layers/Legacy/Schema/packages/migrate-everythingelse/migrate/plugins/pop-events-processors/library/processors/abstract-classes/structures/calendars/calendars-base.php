<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_CalendarsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_CALENDAR];
    }

    public function isCritical(array $component, array &$props)
    {

        // Allow to set the value from above
        return $this->getProp($component, $props, 'critical');
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Allow to set the calendar to critical: in the homepage in GetPoP, the events may be loading before there is a calendar where to show it, and then it appears all at once, and it looks ugly
        if ($this->isCritical($component, $props)) {
            $this->addJsmethod($ret, 'calendar', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
        } else {
            $this->addJsmethod($ret, 'calendar');
        }
        return $ret;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($controlgroup = $this->getControlgroupSubcomponent($component)) {
            $ret[] = $controlgroup;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($controlgroup = $this->getControlgroupSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['controlgroup'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getModuleOutputName($controlgroup);
        }

        return $ret;
    }

    public function getControlgroupSubcomponent(array $component)
    {
        return [PoP_Module_Processor_CalendarControlGroups::class, PoP_Module_Processor_CalendarControlGroups::COMPONENT_CALENDARCONTROLGROUP_CALENDAR];
    }

    public function getOptions(array $component, array &$props)
    {
        return array();
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $inner = $this->getInnerSubcomponent($component);
        $ret['calendar']['layouts'] = $componentprocessor_manager->getProcessor($inner)->getLayoutSubcomponents($inner);

        if ($options = $this->getOptions($component, $props)) {
            $ret['calendar']['options'] = $options;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // The Calendar uses JS to be rendered, so we need the DB data to be always present in the webplatform
        // Mark the layouts as needing dynamic data, so the DB data is sent to the webplatform also when doing SSR
        if (defined('POP_SSR_INITIALIZED')) {
            $inner = $this->getInnerSubcomponent($component);
            $layouts = $componentprocessor_manager->getProcessor($inner)->getLayoutSubcomponents($inner);
            foreach ($layouts as $layout) {
                $this->setProp($layout, $props, 'needs-dynamic-data', true);
            }
        }

        $this->setProp($component, $props, 'critical', false);
        parent::initModelProps($component, $props);
    }
}
