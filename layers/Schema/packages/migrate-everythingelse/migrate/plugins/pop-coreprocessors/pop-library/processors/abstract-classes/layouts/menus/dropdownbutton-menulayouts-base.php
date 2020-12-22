<?php

abstract class PoP_Module_Processor_DropdownButtonMenuLayoutsBase extends PoP_Module_Processor_MenuLayoutsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_DROPDOWNBUTTON];
    }

    public function getDropdownbtnClass(array $module, array &$props)
    {
        return 'btn-group';
    }
    public function getBtnClass(array $module, array &$props)
    {
        return '';
    }
    public function getDropdownmenuClass(array $module, array &$props)
    {
        return '';
    }
    public function getBtnTitle(array $module, array &$props)
    {
        return '';
    }
    public function innerList(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['dropdown-btn'] = $this->getDropdownbtnClass($module, $props);
        $ret[GD_JS_CLASSES]['dropdown-menu'] = $this->getProp($module, $props, 'dropdownmenu-class');
        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($module, $props);
        $ret[GD_JS_TITLES]['btn'] = $this->getBtnTitle($module, $props);

        if ($this->innerList($module, $props)) {
            $ret['inner-list'] = true;
        }
        
        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->appendProp($module, $props, 'dropdownmenu-class', $this->getDropdownmenuClass($module, $props));
        parent::initModelProps($module, $props);
    }
}
