<?php

abstract class PoP_Module_Processor_PostAuthorNameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_AUTHORNAME];
    }

    public function getUrlField(array $component, array &$props)
    {
        return 'url';
    }

    public function getLinkTarget(array $component, array &$props)
    {
        return '';
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);
    
        $ret[] = $this->getUrlField($component, $props);
        $ret[] = 'displayName';

        return $ret;
    }


    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($target = $this->getLinkTarget($component, $props)) {
            $ret['targets']['link'] = $target;
        }
        $ret['url-field'] = $this->getUrlField($component, $props);

        return $ret;
    }
}
