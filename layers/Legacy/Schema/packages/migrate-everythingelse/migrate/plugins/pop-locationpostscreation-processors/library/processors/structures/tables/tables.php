<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public const MODULE_TABLE_MYLOCATIONPOSTS = 'table-mylocationposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLE_MYLOCATIONPOSTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABLE_MYLOCATIONPOSTS:
                $inners = array(
                    self::MODULE_TABLE_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_TableInners::class, GD_Custom_EM_Module_Processor_TableInners::MODULE_TABLEINNER_MYLOCATIONPOSTS],
                );

                return $inners[$module[1]];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getHeaderTitles(array $module)
    {
        $ret = parent::getHeaderTitles($module);

        switch ($module[1]) {
            case self::MODULE_TABLE_MYLOCATIONPOSTS:
                $ret[] = PoP_LocationPosts_PostNameUtils::getNameUc();
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'pop-locationpostscreation-processors');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'pop-locationpostscreation-processors');
                break;
        }
    
        return $ret;
    }
}


