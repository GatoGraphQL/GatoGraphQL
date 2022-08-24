<?php

class GenericForms_Module_Processor_PageCodes extends PoP_Module_Processor_HTMLPageCodesBase
{
    public final const COMPONENT_PAGECODE_NEWSLETTER = 'pagecode-newsletter';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_PAGECODE_NEWSLETTER,
        );
    }

    public function getPageID(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_PAGECODE_NEWSLETTER:
                return POP_NEWSLETTER_CODEPAGE_NEWSLETTER;
        }
    
        return parent::getPageID($component);
    }
}


