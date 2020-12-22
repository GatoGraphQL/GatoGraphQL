<?php

abstract class PoP_Module_Processor_UrlParamTextFormInputsBase extends PoP_Module_Processor_HiddenIDTextFormInputsBase
{
    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // If not loading the value, it's because we're retrieving the values for these in the front-end
        // if ($this->getProp($module, $props, 'replicable')) {

        // fill the input when showing the modal
        $this->addJsmethod($ret, 'fillURLParamInput');
        // }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {

        // if ($this->getProp($module, $props, 'replicable')) {

        $this->mergeProp(
            $module,
            $props,
            'params',
            array(
                'data-urlparam' => $this->getName($module)
            )
        );
        // }
        
        parent::initModelProps($module, $props);
    }
}
