<?php

abstract class PoP_Module_Processor_FormModalViewComponentsBase extends PoP_Module_Processor_ModalViewComponentsBase
{
    public function getPagesectionJsmethod(array $component, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($component, $props);
        $this->addJsmethod($ret, 'modalForm', $this->getType($component));
        return $ret;
    }
}
