<?php

abstract class PoP_Module_Processor_MenuLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array('id', 'itemDataEntries(flat:true)@itemDataEntries');
    }

    public function getItemClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_CLASSES]['item'] = $this->getItemClass($componentVariation, $props);

        return $ret;
    }
}
