<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserCommunities_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const MODULE_TABLE_MYMEMBERS = 'table-mymembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABLE_MYMEMBERS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABLE_MYMEMBERS:
                $inners = array(
                    self::COMPONENT_TABLE_MYMEMBERS => [PoP_UserCommunities_Module_Processor_TableInners::class, PoP_UserCommunities_Module_Processor_TableInners::COMPONENT_TABLEINNER_MYMEMBERS],
                );

                return $inners[$component[1]];
        }

        return parent::getInnerSubmodule($component);
    }

    public function getHeaderTitles(array $component)
    {
        $ret = parent::getHeaderTitles($component);

        switch ($component[1]) {
            case self::COMPONENT_TABLE_MYMEMBERS:
                $ret[] = TranslationAPIFacade::getInstance()->__('User', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Privileges', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Tags', 'poptheme-wassup');
                break;
        }
    
        return $ret;
    }
}


