<?php

abstract class PoP_Module_Processor_ModalViewComponentsBase extends PoP_Module_Processor_BootstrapViewComponentsBase
{
    public function getPagesectionJsmethod(array $componentVariation, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($componentVariation, $props);
        $this->addJsmethod($ret, 'modal', $this->getType($componentVariation));
        return $ret;
    }

    public function getType(array $componentVariation)
    {
        return 'modal';
    }
}
