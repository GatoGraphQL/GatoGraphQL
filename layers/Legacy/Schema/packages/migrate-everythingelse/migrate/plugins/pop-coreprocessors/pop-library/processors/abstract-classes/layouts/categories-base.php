<?php

abstract class PoP_Module_Processor_CategoriesLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CATEGORIES];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array(
            $this->getCategoriesField($componentVariation, $props),
        );
    }

    public function getCategoriesField(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getLabelClass(array $componentVariation, array &$props)
    {
        return 'label-warning';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['categories-field'] = $this->getCategoriesField($componentVariation, $props);
        $ret[GD_JS_CLASSES] = array(
            'label' => $this->getLabelClass($componentVariation, $props),
        );

        return $ret;
    }
}
