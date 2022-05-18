<?php

abstract class PoP_Module_Processor_DropdownButtonMenuLayoutsBase extends PoP_Module_Processor_MenuLayoutsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_DROPDOWNBUTTON];
    }

    public function getDropdownbtnClass(array $componentVariation, array &$props)
    {
        return 'btn-group';
    }
    public function getBtnClass(array $componentVariation, array &$props)
    {
        return '';
    }
    public function getDropdownmenuClass(array $componentVariation, array &$props)
    {
        return '';
    }
    public function getBtnTitle(array $componentVariation, array &$props)
    {
        return '';
    }
    public function innerList(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_CLASSES]['dropdown-btn'] = $this->getDropdownbtnClass($componentVariation, $props);
        $ret[GD_JS_CLASSES]['dropdown-menu'] = $this->getProp($componentVariation, $props, 'dropdownmenu-class');
        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($componentVariation, $props);
        $ret[GD_JS_TITLES]['btn'] = $this->getBtnTitle($componentVariation, $props);

        if ($this->innerList($componentVariation, $props)) {
            $ret['inner-list'] = true;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'dropdownmenu-class', $this->getDropdownmenuClass($componentVariation, $props));
        parent::initModelProps($componentVariation, $props);
    }
}
