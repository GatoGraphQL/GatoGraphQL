<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserCommunities_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public const MODULE_TABLE_MYMEMBERS = 'table-mymembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLE_MYMEMBERS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABLE_MYMEMBERS:
                $inners = array(
                    self::MODULE_TABLE_MYMEMBERS => [PoP_UserCommunities_Module_Processor_TableInners::class, PoP_UserCommunities_Module_Processor_TableInners::MODULE_TABLEINNER_MYMEMBERS],
                );

                return $inners[$module[1]];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getHeaderTitles(array $module)
    {
        $ret = parent::getHeaderTitles($module);

        switch ($module[1]) {
            case self::MODULE_TABLE_MYMEMBERS:
                $ret[] = TranslationAPIFacade::getInstance()->__('User', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Privileges', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Tags', 'poptheme-wassup');
                break;
        }
    
        return $ret;
    }
}


