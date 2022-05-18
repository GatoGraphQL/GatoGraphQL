<?php

abstract class PoP_Module_Processor_CalendarContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);
        $ret[] = 'volunteersNeeded';
        $ret[] = 'url';
        if ($this->getProp($component, $props, 'show-title')) {
            $ret[] = 'title';
        }
        return $ret;
    }

    // Comment Leo 18/07/2017: commented since removing the "hidden-md hidden-lg" view for smartphones. Now there is only one
    // function getJsmethods(array $component, array &$props) {

    //     $ret = parent::getJsmethods($component, $props);
    //     $this->addJsmethod($ret, 'doNothing', 'void-link');
    //     return $ret;
    // }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->getProp($component, $props, 'show-title')) {
            $ret['show-title'] = true;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Show the title by default
        $this->setProp($component, $props, 'show-title', true);
        parent::initModelProps($component, $props);
    }
}
