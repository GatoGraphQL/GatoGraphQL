<?php

abstract class PoP_Module_Processor_CalendarContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);
        $ret[] = 'volunteersNeeded';
        $ret[] = 'url';
        if ($this->getProp($componentVariation, $props, 'show-title')) {
            $ret[] = 'title';
        }
        return $ret;
    }

    // Comment Leo 18/07/2017: commented since removing the "hidden-md hidden-lg" view for smartphones. Now there is only one
    // function getJsmethods(array $componentVariation, array &$props) {

    //     $ret = parent::getJsmethods($componentVariation, $props);
    //     $this->addJsmethod($ret, 'doNothing', 'void-link');
    //     return $ret;
    // }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($this->getProp($componentVariation, $props, 'show-title')) {
            $ret['show-title'] = true;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Show the title by default
        $this->setProp($componentVariation, $props, 'show-title', true);
        parent::initModelProps($componentVariation, $props);
    }
}
