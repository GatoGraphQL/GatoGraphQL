<?php

abstract class PoP_Module_Processor_LatestCountScriptsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LATESTCOUNTSCRIPT];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        return array_merge(
            parent::getLeafComponentFields($component, $props),
            array('latestcountsTriggerValues', 'authors', 'tags', 'references')
        );
    }
}
