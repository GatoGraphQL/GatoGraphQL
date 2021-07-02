<?php

class GD_Custom_Module_Processor_TagMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_SIDEBARMULTICOMPONENT_TAGLEFT = 'sidebarmulticomponent-tagleft';
    public const MODULE_SIDEBARMULTICOMPONENT_TAGRIGHT = 'sidebarmulticomponent-tagright';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_TAGLEFT],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_TAGRIGHT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_TAGLEFT:
                $ret[] = [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::MODULE_TAGSOCIALMEDIA];
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_TAGRIGHT:
                $ret[] = [GD_Custom_Module_Processor_TagWidgets::class, GD_Custom_Module_Processor_TagWidgets::MODULE_WIDGETCOMPACT_TAGINFO];
                break;
        }

        return $ret;
    }
}



