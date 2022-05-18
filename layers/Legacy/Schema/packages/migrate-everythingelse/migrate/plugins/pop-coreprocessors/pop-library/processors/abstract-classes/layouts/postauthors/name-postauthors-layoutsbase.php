<?php

abstract class PoP_Module_Processor_PostAuthorNameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_AUTHORNAME];
    }

    public function getUrlField(array $module, array &$props)
    {
        return 'url';
    }

    public function getLinkTarget(array $module, array &$props)
    {
        return '';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);
    
        $ret[] = $this->getUrlField($module, $props);
        $ret[] = 'displayName';

        return $ret;
    }


    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($target = $this->getLinkTarget($module, $props)) {
            $ret['targets']['link'] = $target;
        }
        $ret['url-field'] = $this->getUrlField($module, $props);

        return $ret;
    }
}
