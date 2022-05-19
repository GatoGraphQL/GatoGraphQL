<?php

abstract class PoP_Module_Processor_TagMentionComponentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTTAG_MENTION_COMPONENT];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array('mentionQueryby', 'namedescription', 'symbolnamedescription');
    }
}
