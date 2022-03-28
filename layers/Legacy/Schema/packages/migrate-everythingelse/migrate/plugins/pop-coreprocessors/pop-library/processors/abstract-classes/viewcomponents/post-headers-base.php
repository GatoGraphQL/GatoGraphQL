<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostViewComponentHeadersBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_POST];
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $thumb = $this->getThumbField($module, $props);
        $data_fields = array('id', 'title', $thumb);
        if ($this->headerShowUrl($module, $props)) {
            $data_fields[] = 'url';
        }

        return $data_fields;
    }

    public function headerShowUrl(array $module, array &$props)
    {
        return false;
    }

    public function getThumbField(array $module, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($module, $props), 
            $this->getThumbFieldArgs($module, $props),
            $this->getThumbFieldAlias($module, $props)
        );
    }

    protected function getThumbFieldName(array $module, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $module, array &$props)
    {
        return ['size' => 'thumb-xs'];
    }

    protected function getThumbFieldAlias(array $module, array &$props)
    {
        return 'thumb';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($module, $props)) {
            $ret['header-show-url'] = true;
        }

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($module, $props, 'succeeding-typeResolver'),
                $this->getThumbField($module, $props))
        );
        
        return $ret;
    }
}
