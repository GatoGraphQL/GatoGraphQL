<?php

abstract class PoP_Module_Processor_PostAdditionalLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        return array('multilayoutKeys');
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-multilayout-label');
        parent::initModelProps($component, $props);
    }
}
