<?php

abstract class PoP_Module_Processor_CollapsePanelGroupComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_COLLAPSEPANELGROUP];
    }

    public function getDropdownItems(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }

    public function getPanelactiveClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'in';
    }

    public function getBootstrapcomponentType(\PoP\ComponentModel\Component\Component $component)
    {
        return 'collapse';
    }

    public function getPaneltitleHtmltag(\PoP\ComponentModel\Component\Component $component)
    {
        return 'h3';
    }

    public function getPaneltitleClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'panel-title';
    }

    public function getOuterpanelClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'panel panel-default';
    }

    public function getPanelbodyClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'panel-body';
    }

    public function closeParent(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }
    
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($dropdown_items = $this->getDropdownItems($component)) {
            $ret['dropdown-items'] = $dropdown_items;
        }

        if ($title_htmltag = $this->getPaneltitleHtmltag($component)) {
            $ret['html-tags']['title'] = $title_htmltag;
        }

        if ($closeParent = $this->closeParent($component)) {
            $ret['close-parent'] = true;
        }

        if ($body_class = $this->getPanelbodyClass($component)) {
            $ret[GD_JS_CLASSES]['body'] = $body_class;
        }

        if ($title_class = $this->getPaneltitleClass($component)) {
            $ret[GD_JS_CLASSES]['title'] = $title_class;
        }

        if ($panel_class = $this->getOuterpanelClass($component)) {
            $ret[GD_JS_CLASSES]['collapsepanel'] = $panel_class;
        }
        
        return $ret;
    }
}
