<?php

abstract class PoP_Module_Processor_MultiTargetIndentMenuLayoutsBase extends PoP_Module_Processor_MenuLayoutsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_MULTITARGETINDENT];
    }

    public function getTargets(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }
    // function getDropdownmenuClass(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     return '';
    // }
    public function getMultitargetClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }
    public function getMultitargetTooltip(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->getMultitargetTooltip($component, $props)) {
            $this->addJsmethod($ret, 'tooltip', 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['targets'] = $this->getTargets($component, $props);
        
        if ($class = $this->getMultitargetClass($component, $props)) {
            $ret[GD_JS_CLASSES]['multitarget'] = $class;
        }
        if ($tooltip = $this->getMultitargetTooltip($component, $props)) {
            $ret[GD_JS_TITLES]['tooltip'] = $tooltip;
        }

        return $ret;
    }
}
