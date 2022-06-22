<?php

abstract class PoP_Module_Processor_PostAuthorNameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_AUTHORNAME];
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'url';
    }

    public function getLinkTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFieldNodes($component, $props);
    
        $ret[] = $this->getUrlField($component, $props);
        $ret[] = 'displayName';

        return $ret;
    }


    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($target = $this->getLinkTarget($component, $props)) {
            $ret['targets']['link'] = $target;
        }
        $ret['url-field'] = $this->getUrlField($component, $props);

        return $ret;
    }
}
