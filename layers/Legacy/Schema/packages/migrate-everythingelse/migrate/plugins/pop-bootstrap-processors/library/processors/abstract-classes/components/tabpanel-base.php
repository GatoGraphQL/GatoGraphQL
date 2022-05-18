<?php

abstract class PoP_Module_Processor_TabPanelComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_TABPANEL];
    }

    protected function isMandatoryActivePanel(array $componentVariation)
    {
        return true;
    }

    public function getPanelHeaderType(array $componentVariation)
    {
        return 'tab';
    }

    public function getPanelactiveClass(array $componentVariation)
    {
        return 'active';
    }

    public function getBootstrapcomponentType(array $componentVariation)
    {
        return 'tabpanel';
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'activeTabLink', 'tablink');
        return $ret;
    }

    protected function getContentClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($content_class = $this->getContentClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['content'] = $content_class;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Through this class, we can identify the blockgroups with tabpanels and place the controlgroup_bottom to the right of the tabs
        $this->appendProp($componentVariation, $props, 'class', 'blockgroup-tabpanel');
        parent::initModelProps($componentVariation, $props);
    }
}
