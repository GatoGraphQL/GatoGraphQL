<?php

class GD_URE_Module_Processor_ProfileFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_EDITMEMBERSHIP = 'forminner-editmembership';
    public final const MODULE_FORMINNER_MYCOMMUNITIES_UPDATE = 'forminner-mycommunities-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_EDITMEMBERSHIP],
            [self::class, self::MODULE_FORMINNER_MYCOMMUNITIES_UPDATE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_FORMINNER_EDITMEMBERSHIP:
                $ret[] = [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_MEMBERSTATUS];
                $ret[] = [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_MEMBERPRIVILEGES];
                $ret[] = [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_MEMBERTAGS];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SAVE];
                break;

            case self::MODULE_FORMINNER_MYCOMMUNITIES_UPDATE:
                $ret[] = [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SAVE];
                break;
        }

        return $ret;
    }
}



