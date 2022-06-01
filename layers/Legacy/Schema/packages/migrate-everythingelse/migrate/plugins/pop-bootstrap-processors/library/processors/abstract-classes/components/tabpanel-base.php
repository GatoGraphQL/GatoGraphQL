<?php

abstract class PoP_Module_Processor_TabPanelComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_TABPANEL];
    }

    protected function isMandatoryActivePanel(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }

    public function getPanelHeaderType(\PoP\ComponentModel\Component\Component $component)
    {
        return 'tab';
    }

    public function getPanelactiveClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'active';
    }

    public function getBootstrapcomponentType(\PoP\ComponentModel\Component\Component $component)
    {
        return 'tabpanel';
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'activeTabLink', 'tablink');
        return $ret;
    }

    protected function getContentClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($content_class = $this->getContentClass($component, $props)) {
            $ret[GD_JS_CLASSES]['content'] = $content_class;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Through this class, we can identify the blockgroups with tabpanels and place the controlgroup_bottom to the right of the tabs
        $this->appendProp($component, $props, 'class', 'blockgroup-tabpanel');
        parent::initModelProps($component, $props);
    }
}
