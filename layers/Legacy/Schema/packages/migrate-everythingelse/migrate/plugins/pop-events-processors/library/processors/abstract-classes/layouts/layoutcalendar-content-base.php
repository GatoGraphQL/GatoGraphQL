<?php

abstract class PoP_Module_Processor_CalendarContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);
        $ret[] = 'volunteersNeeded';
        $ret[] = 'url';
        if ($this->getProp($component, $props, 'show-title')) {
            $ret[] = 'title';
        }
        return $ret;
    }

    // Comment Leo 18/07/2017: commented since removing the "hidden-md hidden-lg" view for smartphones. Now there is only one
    // function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     $ret = parent::getJsmethods($component, $props);
    //     $this->addJsmethod($ret, 'doNothing', 'void-link');
    //     return $ret;
    // }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->getProp($component, $props, 'show-title')) {
            $ret['show-title'] = true;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Show the title by default
        $this->setProp($component, $props, 'show-title', true);
        parent::initModelProps($component, $props);
    }
}
