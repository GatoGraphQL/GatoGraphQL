<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_SimpleViewPreviewPostLayouts extends PoP_Module_Processor_BareSimpleViewPreviewPostLayoutsBase
{
    public const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW = 'layout-automatedemails-previewpost-event-simpleview';
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW],
        );
    }


    public function getAuthorModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::MODULE_LAYOUTPOST_AUTHORNAME];
        }

        return parent::getAuthorModule($module);
    }

    public function getAbovecontentSubmodules(array $module)
    {
        $ret = parent::getAbovecontentSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW:
                $ret[] = [GD_EM_Module_Processor_EventMultipleComponents::class, GD_EM_Module_Processor_EventMultipleComponents::MODULE_MULTICOMPONENT_EVENT_DATELOCATION];
                break;
        }

        return $ret;
    }

    public function getAftercontentSubmodules(array $module)
    {
        $ret = parent::getAftercontentSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW:
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::class, PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::MODULE_QUICKLINKGROUP_EVENTBOTTOM];
                break;
        }

        return $ret;
    }
}


