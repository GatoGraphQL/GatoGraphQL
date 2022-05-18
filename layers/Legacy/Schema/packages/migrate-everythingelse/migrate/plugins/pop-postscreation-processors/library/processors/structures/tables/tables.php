<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const MODULE_TABLE_MYLINKS = 'table-mylinks';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLE_MYLINKS],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TABLE_MYLINKS:
                $inners = array(
                    self::MODULE_TABLE_MYLINKS => [PoP_ContentPostLinksCreation_Module_Processor_TableInners::class, PoP_ContentPostLinksCreation_Module_Processor_TableInners::MODULE_TABLEINNER_MYLINKS],
                );

                return $inners[$componentVariation[1]];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getHeaderTitles(array $componentVariation)
    {
        $ret = parent::getHeaderTitles($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_TABLE_MYLINKS:
                $ret[] = TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;
        }
    
        return $ret;
    }
}


