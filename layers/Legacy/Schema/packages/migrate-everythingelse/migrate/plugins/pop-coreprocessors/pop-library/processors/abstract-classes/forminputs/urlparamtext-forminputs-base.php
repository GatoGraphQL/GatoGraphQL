<?php

abstract class PoP_Module_Processor_UrlParamTextFormInputsBase extends PoP_Module_Processor_HiddenIDTextFormInputsBase
{
    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // If not loading the value, it's because we're retrieving the values for these in the front-end
        // if ($this->getProp($component, $props, 'replicable')) {

        // fill the input when showing the modal
        $this->addJsmethod($ret, 'fillURLParamInput');
        // }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // if ($this->getProp($component, $props, 'replicable')) {

        $this->mergeProp(
            $component,
            $props,
            'params',
            array(
                'data-urlparam' => $this->getName($component)
            )
        );
        // }

        parent::initModelProps($component, $props);
    }
}
