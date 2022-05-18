<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_POST];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $thumb = $this->getThumbField($component, $props);
        $data_fields = array('id', 'title', $thumb);
        if ($this->headerShowUrl($component, $props)) {
            $data_fields[] = 'url';
        }

        return $data_fields;
    }

    public function headerShowUrl(array $component, array &$props)
    {
        return false;
    }

    public function getThumbField(array $component, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($component, $props), 
            $this->getThumbFieldArgs($component, $props),
            $this->getThumbFieldAlias($component, $props)
        );
    }

    protected function getThumbFieldName(array $component, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $component, array &$props)
    {
        return ['size' => 'thumb-xs'];
    }

    protected function getThumbFieldAlias(array $component, array &$props)
    {
        return 'thumb';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($component, $props)) {
            $ret['header-show-url'] = true;
        }

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $this->getThumbField($component, $props))
        );
        
        return $ret;
    }
}
