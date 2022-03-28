<?php

abstract class PoP_Module_Processor_MenuLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array('id', 'itemDataEntries(flat:true)@itemDataEntries');
    }

    public function getItemClass(array $module, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['item'] = $this->getItemClass($module, $props);

        return $ret;
    }
}
