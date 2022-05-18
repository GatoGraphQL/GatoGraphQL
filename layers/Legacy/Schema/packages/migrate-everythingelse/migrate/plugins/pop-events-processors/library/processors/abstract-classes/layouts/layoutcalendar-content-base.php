<?php

abstract class PoP_Module_Processor_CalendarContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);
        $ret[] = 'volunteersNeeded';
        $ret[] = 'url';
        if ($this->getProp($module, $props, 'show-title')) {
            $ret[] = 'title';
        }
        return $ret;
    }

    // Comment Leo 18/07/2017: commented since removing the "hidden-md hidden-lg" view for smartphones. Now there is only one
    // function getJsmethods(array $module, array &$props) {

    //     $ret = parent::getJsmethods($module, $props);
    //     $this->addJsmethod($ret, 'doNothing', 'void-link');
    //     return $ret;
    // }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($this->getProp($module, $props, 'show-title')) {
            $ret['show-title'] = true;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Show the title by default
        $this->setProp($module, $props, 'show-title', true);
        parent::initModelProps($module, $props);
    }
}
