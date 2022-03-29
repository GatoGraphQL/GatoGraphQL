<?php

abstract class PoP_Module_Processor_CategoriesLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_CATEGORIES];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array(
            $this->getCategoriesField($module, $props),
        );
    }

    public function getCategoriesField(array $module, array &$props)
    {
        return null;
    }
    public function getLabelClass(array $module, array &$props)
    {
        return 'label-warning';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['categories-field'] = $this->getCategoriesField($module, $props);
        $ret[GD_JS_CLASSES] = array(
            'label' => $this->getLabelClass($module, $props),
        );

        return $ret;
    }
}
