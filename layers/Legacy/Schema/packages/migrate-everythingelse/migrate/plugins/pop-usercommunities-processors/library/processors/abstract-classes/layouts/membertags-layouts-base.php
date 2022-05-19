<?php

abstract class GD_URE_Module_Processor_MemberTagsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [URE_PoPProcessors_TemplateResourceLoaderProcessor::class, URE_PoPProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_MEMBERTAGS];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array('memberTagsByName');
    }

    public function getDescription(array $component, array &$props)
    {
        return '';
    }

    public function getLabelClass(array $component, array &$props)
    {
        return 'label-info';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($description = $this->getDescription($component, $props)) {
            $ret[GD_JS_TITLES]['description'] = $description;
        }

        if ($label_class = $this->getLabelClass($component, $props)) {
            $ret[GD_JS_CLASSES]['label'] = $label_class;
        }
    
        return $ret;
    }
}
