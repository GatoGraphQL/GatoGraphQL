<?php

abstract class PoP_Module_Processor_PostAuthorNameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_AUTHORNAME];
    }

    public function getUrlField(array $componentVariation, array &$props)
    {
        return 'url';
    }

    public function getLinkTarget(array $componentVariation, array &$props)
    {
        return '';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);
    
        $ret[] = $this->getUrlField($componentVariation, $props);
        $ret[] = 'displayName';

        return $ret;
    }


    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($target = $this->getLinkTarget($componentVariation, $props)) {
            $ret['targets']['link'] = $target;
        }
        $ret['url-field'] = $this->getUrlField($componentVariation, $props);

        return $ret;
    }
}
