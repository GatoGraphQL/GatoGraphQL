<?php

abstract class PoP_Module_Processor_CollapsePanelGroupComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_COLLAPSEPANELGROUP];
    }

    public function getDropdownItems(array $module)
    {
        return array();
    }

    public function getPanelactiveClass(array $module)
    {
        return 'in';
    }

    public function getBootstrapcomponentType(array $module)
    {
        return 'collapse';
    }

    public function getPaneltitleHtmltag(array $module)
    {
        return 'h3';
    }

    public function getPaneltitleClass(array $module)
    {
        return 'panel-title';
    }

    public function getOuterpanelClass(array $module)
    {
        return 'panel panel-default';
    }

    public function getPanelbodyClass(array $module)
    {
        return 'panel-body';
    }

    public function closeParent(array $module)
    {
        return true;
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($dropdown_items = $this->getDropdownItems($module)) {
            $ret['dropdown-items'] = $dropdown_items;
        }

        if ($title_htmltag = $this->getPaneltitleHtmltag($module)) {
            $ret['html-tags']['title'] = $title_htmltag;
        }

        if ($closeParent = $this->closeParent($module)) {
            $ret['close-parent'] = true;
        }

        if ($body_class = $this->getPanelbodyClass($module)) {
            $ret[GD_JS_CLASSES]['body'] = $body_class;
        }

        if ($title_class = $this->getPaneltitleClass($module)) {
            $ret[GD_JS_CLASSES]['title'] = $title_class;
        }

        if ($panel_class = $this->getOuterpanelClass($module)) {
            $ret[GD_JS_CLASSES]['collapsepanel'] = $panel_class;
        }
        
        return $ret;
    }
}
