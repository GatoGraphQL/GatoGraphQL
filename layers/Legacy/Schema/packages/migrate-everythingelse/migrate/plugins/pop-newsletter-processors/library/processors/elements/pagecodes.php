<?php

class GenericForms_Module_Processor_PageCodes extends PoP_Module_Processor_HTMLPageCodesBase
{
    public final const COMPONENT_PAGECODE_NEWSLETTER = 'pagecode-newsletter';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_PAGECODE_NEWSLETTER],
        );
    }

    public function getPageId(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_PAGECODE_NEWSLETTER:
                return POP_NEWSLETTER_CODEPAGE_NEWSLETTER;
        }
    
        return parent::getPageId($component);
    }
}


