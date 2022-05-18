<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostTypeaheadComponentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_TYPEAHEAD_COMPONENT];
    }

    public function getThumbField(array $component, array &$props): array
    {
        return FieldQueryInterpreterFacade::getInstance()->getField('thumb', ['size' => 'thumb-xs'], 'thumb');
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $thumb = $this->getThumbField($component, $props);
        return array('id', $thumb, 'title', 'url');
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $thumb = $this->getThumbField($component, $props);
        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $thumb),
        );
        
        return $ret;
    }
}
