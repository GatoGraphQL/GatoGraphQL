<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostCardLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_CARD];
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

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $this->getThumbField($component, $props)
            ),
        );

        return $ret;
    }
}
