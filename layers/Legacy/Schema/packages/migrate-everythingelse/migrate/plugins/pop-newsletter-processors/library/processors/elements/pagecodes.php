<?php

class GenericForms_Module_Processor_PageCodes extends PoP_Module_Processor_HTMLPageCodesBase
{
    public final const MODULE_PAGECODE_NEWSLETTER = 'pagecode-newsletter';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGECODE_NEWSLETTER],
        );
    }

    public function getPageId(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_PAGECODE_NEWSLETTER:
                return POP_NEWSLETTER_CODEPAGE_NEWSLETTER;
        }
    
        return parent::getPageId($module);
    }
}


