<?php

abstract class PoP_Module_Processor_AuthorContentLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_AUTHORCONTENT];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array('displayName', 'descriptionFormatted', 'shortDescriptionFormatted');
    }

    public function getDescriptionMaxlength(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($maxlength = $this->getDescriptionMaxlength($module, $props)) {
            $ret['description-maxlength'] = $maxlength;
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($maxlength = $this->getDescriptionMaxlength($module, $props)) {
            $this->addJsmethod($ret, 'showmore');
        }

        return $ret;
    }
}
