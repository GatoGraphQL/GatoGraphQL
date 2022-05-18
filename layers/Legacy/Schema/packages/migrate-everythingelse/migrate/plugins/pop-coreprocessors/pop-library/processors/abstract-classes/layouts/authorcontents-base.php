<?php

abstract class PoP_Module_Processor_AuthorContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_AUTHORCONTENT];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array('displayName', 'descriptionFormatted', 'shortDescriptionFormatted');
    }

    public function getDescriptionMaxlength(array $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($maxlength = $this->getDescriptionMaxlength($component, $props)) {
            $ret['description-maxlength'] = $maxlength;
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($maxlength = $this->getDescriptionMaxlength($component, $props)) {
            $this->addJsmethod($ret, 'showmore');
        }

        return $ret;
    }
}
