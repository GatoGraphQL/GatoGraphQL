<?php

abstract class PoP_Module_Processor_FormModalViewComponentsBase extends PoP_Module_Processor_ModalViewComponentsBase
{
    public function getPagesectionJsmethod(array $module, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($module, $props);
        $this->addJsmethod($ret, 'modalForm', $this->getType($module));
        return $ret;
    }
}
