<?php

abstract class PoP_Module_Processor_MultiTargetIndentMenuLayoutsBase extends PoP_Module_Processor_MenuLayoutsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_MULTITARGETINDENT];
    }

    public function getTargets(array $componentVariation, array &$props)
    {
        return array();
    }
    // function getDropdownmenuClass(array $componentVariation, array &$props) {

    //     return '';
    // }
    public function getMultitargetClass(array $componentVariation, array &$props)
    {
        return '';
    }
    public function getMultitargetTooltip(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->getMultitargetTooltip($componentVariation, $props)) {
            $this->addJsmethod($ret, 'tooltip', 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['targets'] = $this->getTargets($componentVariation, $props);
        
        if ($class = $this->getMultitargetClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['multitarget'] = $class;
        }
        if ($tooltip = $this->getMultitargetTooltip($componentVariation, $props)) {
            $ret[GD_JS_TITLES]['tooltip'] = $tooltip;
        }

        return $ret;
    }
}
