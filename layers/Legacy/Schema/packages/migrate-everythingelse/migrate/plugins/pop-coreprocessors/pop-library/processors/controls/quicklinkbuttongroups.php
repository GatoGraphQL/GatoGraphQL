<?php

class PoP_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTSHARE = 'quicklinkbuttongroup-postshare';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTPERMALINK = 'quicklinkbuttongroup-postpermalink';
    public final const MODULE_QUICKLINKBUTTONGROUP_USERSHARE = 'quicklinkbuttongroup-usershare';
    public final const MODULE_QUICKLINKBUTTONGROUP_USERCONTACTINFO = 'quicklinkbuttongroup-usercontactinfo';
    public final const MODULE_QUICKLINKBUTTONGROUP_COMMENTS = 'quicklinkbuttongroup-comments';
    public final const MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL = 'quicklinkbuttongroup-comments-label';
    public final const MODULE_QUICKLINKBUTTONGROUP_TAGSHARE = 'quicklinkbuttongroup-tagshare';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTSHARE],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTPERMALINK],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_USERSHARE],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_USERCONTACTINFO],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_COMMENTS],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_TAGSHARE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_QUICKLINKBUTTONGROUP_POSTSHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonQuicklinks::class, PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_POSTPERMALINK:
                $ret[] = [PoP_Module_Processor_ButtonWrappers::class, PoP_Module_Processor_ButtonWrappers::MODULE_BUTTONWRAPPER_POSTPERMALINK];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_USERSHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonQuicklinks::class, PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_USERCONTACTINFO:
                $ret[] = [PoP_Module_Processor_DropdownButtonQuicklinks::class, PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_COMMENTS:
                $ret[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_POSTCOMMENTS];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL:
                $ret[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_POSTCOMMENTS_LABEL];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_TAGSHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonQuicklinks::class, PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE];
                break;
        }
        
        return $ret;
    }
}


