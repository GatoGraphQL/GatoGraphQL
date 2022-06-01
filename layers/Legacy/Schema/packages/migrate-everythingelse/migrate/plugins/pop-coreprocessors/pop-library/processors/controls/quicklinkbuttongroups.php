<?php

class PoP_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_QUICKLINKBUTTONGROUP_POSTSHARE = 'quicklinkbuttongroup-postshare';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_POSTPERMALINK = 'quicklinkbuttongroup-postpermalink';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_USERSHARE = 'quicklinkbuttongroup-usershare';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_USERCONTACTINFO = 'quicklinkbuttongroup-usercontactinfo';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS = 'quicklinkbuttongroup-comments';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL = 'quicklinkbuttongroup-comments-label';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_TAGSHARE = 'quicklinkbuttongroup-tagshare';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_QUICKLINKBUTTONGROUP_POSTSHARE,
            self::COMPONENT_QUICKLINKBUTTONGROUP_POSTPERMALINK,
            self::COMPONENT_QUICKLINKBUTTONGROUP_USERSHARE,
            self::COMPONENT_QUICKLINKBUTTONGROUP_USERCONTACTINFO,
            self::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS,
            self::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL,
            self::COMPONENT_QUICKLINKBUTTONGROUP_TAGSHARE,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTSHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonQuicklinks::class, PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTPERMALINK:
                $ret[] = [PoP_Module_Processor_ButtonWrappers::class, PoP_Module_Processor_ButtonWrappers::COMPONENT_BUTTONWRAPPER_POSTPERMALINK];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_USERSHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonQuicklinks::class, PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_USERCONTACTINFO:
                $ret[] = [PoP_Module_Processor_DropdownButtonQuicklinks::class, PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS:
                $ret[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_POSTCOMMENTS];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL:
                $ret[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_POSTCOMMENTS_LABEL];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_TAGSHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonQuicklinks::class, PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE];
                break;
        }
        
        return $ret;
    }
}


