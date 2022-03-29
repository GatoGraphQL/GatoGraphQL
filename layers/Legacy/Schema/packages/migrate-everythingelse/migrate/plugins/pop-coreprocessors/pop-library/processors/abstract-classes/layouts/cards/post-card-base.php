<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostCardLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_CARD];
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

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $thumb = $this->getThumbField($module, $props);
        return array('id', $thumb, 'title', 'url');
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($module, $props, 'succeeding-typeResolver'),
                $this->getThumbField($module, $props)
            ),
        );

        return $ret;
    }
}
