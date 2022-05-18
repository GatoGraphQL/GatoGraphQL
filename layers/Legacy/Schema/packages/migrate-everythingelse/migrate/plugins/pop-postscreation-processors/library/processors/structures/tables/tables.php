<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const MODULE_TABLE_MYLINKS = 'table-mylinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLE_MYLINKS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_TABLE_MYLINKS:
                $inners = array(
                    self::MODULE_TABLE_MYLINKS => [PoP_ContentPostLinksCreation_Module_Processor_TableInners::class, PoP_ContentPostLinksCreation_Module_Processor_TableInners::MODULE_TABLEINNER_MYLINKS],
                );

                return $inners[$component[1]];
        }

        return parent::getInnerSubmodule($component);
    }

    public function getHeaderTitles(array $component)
    {
        $ret = parent::getHeaderTitles($component);

        switch ($component[1]) {
            case self::MODULE_TABLE_MYLINKS:
                $ret[] = TranslationAPIFacade::getInstance()->__('Link', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;
        }
    
        return $ret;
    }
}


