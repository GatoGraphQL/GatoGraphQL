<?php

abstract class PoP_Module_Processor_UrlParamTextFormInputsBase extends PoP_Module_Processor_HiddenIDTextFormInputsBase
{
    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // If not loading the value, it's because we're retrieving the values for these in the front-end
        // if ($this->getProp($componentVariation, $props, 'replicable')) {

        // fill the input when showing the modal
        $this->addJsmethod($ret, 'fillURLParamInput');
        // }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // if ($this->getProp($componentVariation, $props, 'replicable')) {

        $this->mergeProp(
            $componentVariation,
            $props,
            'params',
            array(
                'data-urlparam' => $this->getName($componentVariation)
            )
        );
        // }

        parent::initModelProps($componentVariation, $props);
    }
}
