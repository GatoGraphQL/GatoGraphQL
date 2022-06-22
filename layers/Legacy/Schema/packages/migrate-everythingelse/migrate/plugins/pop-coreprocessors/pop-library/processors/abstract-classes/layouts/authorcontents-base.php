<?php

abstract class PoP_Module_Processor_AuthorContentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_AUTHORCONTENT];
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        return array('displayName', 'descriptionFormatted', 'shortDescriptionFormatted');
    }

    public function getDescriptionMaxlength(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($maxlength = $this->getDescriptionMaxlength($component, $props)) {
            $ret['description-maxlength'] = $maxlength;
        }

        return $ret;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($maxlength = $this->getDescriptionMaxlength($component, $props)) {
            $this->addJsmethod($ret, 'showmore');
        }

        return $ret;
    }
}
