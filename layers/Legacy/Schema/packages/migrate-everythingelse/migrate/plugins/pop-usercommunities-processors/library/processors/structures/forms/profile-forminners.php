<?php

class GD_URE_Module_Processor_ProfileFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_EDITMEMBERSHIP = 'forminner-editmembership';
    public final const COMPONENT_FORMINNER_MYCOMMUNITIES_UPDATE = 'forminner-mycommunities-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_EDITMEMBERSHIP],
            [self::class, self::COMPONENT_FORMINNER_MYCOMMUNITIES_UPDATE],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);
    
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_EDITMEMBERSHIP:
                $ret[] = [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::COMPONENT_URE_FORMINPUTGROUP_MEMBERSTATUS];
                $ret[] = [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::COMPONENT_URE_FORMINPUTGROUP_MEMBERPRIVILEGES];
                $ret[] = [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::COMPONENT_URE_FORMINPUTGROUP_MEMBERTAGS];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SAVE];
                break;

            case self::COMPONENT_FORMINNER_MYCOMMUNITIES_UPDATE:
                $ret[] = [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SAVE];
                break;
        }

        return $ret;
    }
}



