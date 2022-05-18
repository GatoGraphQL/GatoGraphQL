<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_POST];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $thumb = $this->getThumbField($componentVariation, $props);
        $data_fields = array('id', 'title', $thumb);
        if ($this->headerShowUrl($componentVariation, $props)) {
            $data_fields[] = 'url';
        }

        return $data_fields;
    }

    public function headerShowUrl(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getThumbField(array $componentVariation, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($componentVariation, $props), 
            $this->getThumbFieldArgs($componentVariation, $props),
            $this->getThumbFieldAlias($componentVariation, $props)
        );
    }

    protected function getThumbFieldName(array $componentVariation, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $componentVariation, array &$props)
    {
        return ['size' => 'thumb-xs'];
    }

    protected function getThumbFieldAlias(array $componentVariation, array &$props)
    {
        return 'thumb';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
    
        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($componentVariation, $props)) {
            $ret['header-show-url'] = true;
        }

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($componentVariation, $props, 'succeeding-typeResolver'),
                $this->getThumbField($componentVariation, $props))
        );
        
        return $ret;
    }
}
