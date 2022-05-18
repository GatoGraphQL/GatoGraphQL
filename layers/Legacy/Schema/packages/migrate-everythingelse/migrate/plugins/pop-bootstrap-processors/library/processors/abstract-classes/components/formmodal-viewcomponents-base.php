<?php

abstract class PoP_Module_Processor_FormModalViewComponentsBase extends PoP_Module_Processor_ModalViewComponentsBase
{
    public function getPagesectionJsmethod(array $componentVariation, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($componentVariation, $props);
        $this->addJsmethod($ret, 'modalForm', $this->getType($componentVariation));
        return $ret;
    }
}
