<?php

abstract class PoP_Module_Processor_ModalViewComponentsBase extends PoP_Module_Processor_BootstrapViewComponentsBase
{
    public function getPagesectionJsmethod(array $module, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($module, $props);
        $this->addJsmethod($ret, 'modal', $this->getType($module));
        return $ret;
    }

    public function getType(array $module)
    {
        return 'modal';
    }
}
