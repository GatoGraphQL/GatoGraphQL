<?php

abstract class PoP_Module_Processor_ModalViewComponentsBase extends PoP_Module_Processor_BootstrapViewComponentsBase
{
    public function getPagesectionJsmethod(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($component, $props);
        $this->addJsmethod($ret, 'modal', $this->getType($component));
        return $ret;
    }

    public function getType(\PoP\ComponentModel\Component\Component $component)
    {
        return 'modal';
    }
}
