<?php

abstract class PoP_Module_Processor_DropdownButtonMenuLayoutsBase extends PoP_Module_Processor_MenuLayoutsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_DROPDOWNBUTTON];
    }

    public function getDropdownbtnClass(array $component, array &$props)
    {
        return 'btn-group';
    }
    public function getBtnClass(array $component, array &$props)
    {
        return '';
    }
    public function getDropdownmenuClass(array $component, array &$props)
    {
        return '';
    }
    public function getBtnTitle(array $component, array &$props)
    {
        return '';
    }
    public function innerList(array $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['dropdown-btn'] = $this->getDropdownbtnClass($component, $props);
        $ret[GD_JS_CLASSES]['dropdown-menu'] = $this->getProp($component, $props, 'dropdownmenu-class');
        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($component, $props);
        $ret[GD_JS_TITLES]['btn'] = $this->getBtnTitle($component, $props);

        if ($this->innerList($component, $props)) {
            $ret['inner-list'] = true;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'dropdownmenu-class', $this->getDropdownmenuClass($component, $props));
        parent::initModelProps($component, $props);
    }
}
