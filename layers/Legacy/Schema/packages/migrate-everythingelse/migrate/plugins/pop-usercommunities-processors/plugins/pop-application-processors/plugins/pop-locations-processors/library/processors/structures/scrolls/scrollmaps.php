<?php

class PoP_UserCommunities_EM_ModuleProcessor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLL_COMMUNITIES_MAP = 'scroll-communities-map';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_COMMUNITIES_MAP],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_COMMUNITIES_MAP => [PoP_UserCommunities_EM_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_EM_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_COMMUNITIES_MAP],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}

