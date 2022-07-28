<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ConfigurationComponentModel\Facades\HelperServices\TypeResolverHelperServiceFacade;

abstract class PoP_Module_Processor_PostViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_POST];
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $thumb = $this->getThumbField($component, $props);
        $data_fields = array('id', 'title', $thumb);
        if ($this->headerShowUrl($component, $props)) {
            $data_fields[] = 'url';
        }

        return $data_fields;
    }

    public function headerShowUrl(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getThumbField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return /* @todo Re-do this code! Left undone */ new Field(
            $this->getThumbFieldName($component, $props), 
            $this->getThumbFieldArgs($component, $props),
            $this->getThumbFieldAlias($component, $props)
        );
    }

    protected function getThumbFieldName(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return ['size' => 'thumb-xs'];
    }

    protected function getThumbFieldAlias(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'thumb';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($component, $props)) {
            $ret['header-show-url'] = true;
        }

        $ret['thumb'] = array(
            'name' => TypeResolverHelperServiceFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $this->getThumbField($component, $props) // @todo Fix: pass LeafField
            )
        );
        
        return $ret;
    }
}
