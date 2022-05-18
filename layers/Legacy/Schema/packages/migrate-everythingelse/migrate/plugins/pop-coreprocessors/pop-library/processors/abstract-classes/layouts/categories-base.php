<?php

abstract class PoP_Module_Processor_CategoriesLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CATEGORIES];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array(
            $this->getCategoriesField($component, $props),
        );
    }

    public function getCategoriesField(array $component, array &$props)
    {
        return null;
    }
    public function getLabelClass(array $component, array &$props)
    {
        return 'label-warning';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['categories-field'] = $this->getCategoriesField($component, $props);
        $ret[GD_JS_CLASSES] = array(
            'label' => $this->getLabelClass($component, $props),
        );

        return $ret;
    }
}
