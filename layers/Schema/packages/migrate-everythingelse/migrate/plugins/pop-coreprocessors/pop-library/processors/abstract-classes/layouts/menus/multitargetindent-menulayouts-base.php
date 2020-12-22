<?php

abstract class PoP_Module_Processor_MultiTargetIndentMenuLayoutsBase extends PoP_Module_Processor_MenuLayoutsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_MULTITARGETINDENT];
    }

    public function getTargets(array $module, array &$props)
    {
        return array();
    }
    // function getDropdownmenuClass(array $module, array &$props) {

    //     return '';
    // }
    public function getMultitargetClass(array $module, array &$props)
    {
        return '';
    }
    public function getMultitargetTooltip(array $module, array &$props)
    {
        return '';
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->getMultitargetTooltip($module, $props)) {
            $this->addJsmethod($ret, 'tooltip', 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['targets'] = $this->getTargets($module, $props);
        
        if ($class = $this->getMultitargetClass($module, $props)) {
            $ret[GD_JS_CLASSES]['multitarget'] = $class;
        }
        if ($tooltip = $this->getMultitargetTooltip($module, $props)) {
            $ret[GD_JS_TITLES]['tooltip'] = $tooltip;
        }

        return $ret;
    }
}
