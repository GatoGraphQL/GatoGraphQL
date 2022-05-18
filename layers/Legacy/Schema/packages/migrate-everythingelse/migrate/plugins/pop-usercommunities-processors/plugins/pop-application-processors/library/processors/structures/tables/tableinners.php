<?php

class PoP_UserCommunities_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const MODULE_TABLEINNER_MYMEMBERS = 'tableinner-mymembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLEINNER_MYMEMBERS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        // Main layout
        switch ($module[1]) {
            case self::MODULE_TABLEINNER_MYMEMBERS:
                $ret[] = [PoP_UserCommunities_Module_Processor_PreviewUserLayouts::class, PoP_UserCommunities_Module_Processor_PreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS];
                $ret[] = [GD_URE_Module_Processor_MemberStatusLayouts::class, GD_URE_Module_Processor_MemberStatusLayouts::MODULE_URE_LAYOUTUSER_MEMBERSTATUS];
                $ret[] = [GD_URE_Module_Processor_MemberPrivilegesLayouts::class, GD_URE_Module_Processor_MemberPrivilegesLayouts::MODULE_URE_LAYOUTUSER_MEMBERPRIVILEGES];
                $ret[] = [GD_URE_Module_Processor_MemberTagsLayouts::class, GD_URE_Module_Processor_MemberTagsLayouts::MODULE_URE_LAYOUTUSER_MEMBERTAGS];
                break;
        }

        return $ret;
    }
}


