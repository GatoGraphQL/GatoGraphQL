<?php

abstract class PoP_Module_Processor_StructureInnersBase extends PoPEngine_QueryDataComponentProcessorBase
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }
    
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layouts'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...), 
                $layouts
            );
        }

        return $ret;
    }
}
