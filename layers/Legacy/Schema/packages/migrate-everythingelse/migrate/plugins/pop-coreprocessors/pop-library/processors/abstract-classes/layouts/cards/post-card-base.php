<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostCardLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_CARD];
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

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $thumb = $this->getThumbField($componentVariation, $props);
        return array('id', $thumb, 'title', 'url');
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($componentVariation, $props, 'succeeding-typeResolver'),
                $this->getThumbField($componentVariation, $props)
            ),
        );

        return $ret;
    }
}
