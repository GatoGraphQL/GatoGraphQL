<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_CalendarsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_CALENDAR];
    }

    public function isCritical(array $module, array &$props)
    {

        // Allow to set the value from above
        return $this->getProp($module, $props, 'critical');
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // Allow to set the calendar to critical: in the homepage in GetPoP, the events may be loading before there is a calendar where to show it, and then it appears all at once, and it looks ugly
        if ($this->isCritical($module, $props)) {
            $this->addJsmethod($ret, 'calendar', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
        } else {
            $this->addJsmethod($ret, 'calendar');
        }
        return $ret;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($controlgroup = $this->getControlgroupSubmodule($module)) {
            $ret[] = $controlgroup;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($controlgroup = $this->getControlgroupSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controlgroup'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controlgroup);
        }

        return $ret;
    }

    public function getControlgroupSubmodule(array $module)
    {
        return [PoP_Module_Processor_CalendarControlGroups::class, PoP_Module_Processor_CalendarControlGroups::MODULE_CALENDARCONTROLGROUP_CALENDAR];
    }

    public function getOptions(array $module, array &$props)
    {
        return array();
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $inner = $this->getInnerSubmodule($module);
        $ret['calendar']['layouts'] = $moduleprocessor_manager->getProcessor($inner)->getLayoutSubmodules($inner);

        if ($options = $this->getOptions($module, $props)) {
            $ret['calendar']['options'] = $options;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // The Calendar uses JS to be rendered, so we need the DB data to be always present in the webplatform
        // Mark the layouts as needing dynamic data, so the DB data is sent to the webplatform also when doing SSR
        if (defined('POP_SSR_INITIALIZED')) {
            $inner = $this->getInnerSubmodule($module);
            $layouts = $moduleprocessor_manager->getProcessor($inner)->getLayoutSubmodules($inner);
            foreach ($layouts as $layout) {
                $this->setProp($layout, $props, 'needs-dynamic-data', true);
            }
        }

        $this->setProp($module, $props, 'critical', false);
        parent::initModelProps($module, $props);
    }
}
