<?php

abstract class PoP_Module_Processor_AuthorContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_AUTHORCONTENT];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array('displayName', 'descriptionFormatted', 'shortDescriptionFormatted');
    }

    public function getDescriptionMaxlength(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($maxlength = $this->getDescriptionMaxlength($componentVariation, $props)) {
            $ret['description-maxlength'] = $maxlength;
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($maxlength = $this->getDescriptionMaxlength($componentVariation, $props)) {
            $this->addJsmethod($ret, 'showmore');
        }

        return $ret;
    }
}
