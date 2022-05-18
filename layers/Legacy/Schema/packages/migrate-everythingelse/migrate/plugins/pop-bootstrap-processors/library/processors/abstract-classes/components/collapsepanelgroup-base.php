<?php

abstract class PoP_Module_Processor_CollapsePanelGroupComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_COLLAPSEPANELGROUP];
    }

    public function getDropdownItems(array $componentVariation)
    {
        return array();
    }

    public function getPanelactiveClass(array $componentVariation)
    {
        return 'in';
    }

    public function getBootstrapcomponentType(array $componentVariation)
    {
        return 'collapse';
    }

    public function getPaneltitleHtmltag(array $componentVariation)
    {
        return 'h3';
    }

    public function getPaneltitleClass(array $componentVariation)
    {
        return 'panel-title';
    }

    public function getOuterpanelClass(array $componentVariation)
    {
        return 'panel panel-default';
    }

    public function getPanelbodyClass(array $componentVariation)
    {
        return 'panel-body';
    }

    public function closeParent(array $componentVariation)
    {
        return true;
    }
    
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($dropdown_items = $this->getDropdownItems($componentVariation)) {
            $ret['dropdown-items'] = $dropdown_items;
        }

        if ($title_htmltag = $this->getPaneltitleHtmltag($componentVariation)) {
            $ret['html-tags']['title'] = $title_htmltag;
        }

        if ($closeParent = $this->closeParent($componentVariation)) {
            $ret['close-parent'] = true;
        }

        if ($body_class = $this->getPanelbodyClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['body'] = $body_class;
        }

        if ($title_class = $this->getPaneltitleClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['title'] = $title_class;
        }

        if ($panel_class = $this->getOuterpanelClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['collapsepanel'] = $panel_class;
        }
        
        return $ret;
    }
}
