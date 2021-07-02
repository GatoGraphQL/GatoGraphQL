<?php

abstract class PoP_Module_Processor_TabPanelComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_TABPANEL];
    }

    protected function isMandatoryActivePanel(array $module)
    {
        return true;
    }

    public function getPanelHeaderType(array $module)
    {
        return 'tab';
    }

    public function getPanelactiveClass(array $module)
    {
        return 'active';
    }

    public function getBootstrapcomponentType(array $module)
    {
        return 'tabpanel';
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'activeTabLink', 'tablink');
        return $ret;
    }

    protected function getContentClass(array $module, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($content_class = $this->getContentClass($module, $props)) {
            $ret[GD_JS_CLASSES]['content'] = $content_class;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Through this class, we can identify the blockgroups with tabpanels and place the controlgroup_bottom to the right of the tabs
        $this->appendProp($module, $props, 'class', 'blockgroup-tabpanel');
        parent::initModelProps($module, $props);
    }
}
