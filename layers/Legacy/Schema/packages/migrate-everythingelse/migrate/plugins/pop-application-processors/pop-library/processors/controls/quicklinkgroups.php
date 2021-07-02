<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Module_Processor_CustomQuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public const MODULE_QUICKLINKGROUP_POST = 'quicklinkgroup-post';
    public const MODULE_QUICKLINKGROUP_POSTBOTTOM = 'quicklinkgroup-postbottom';
    public const MODULE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER = 'quicklinkgroup-postbottomvolunteer';
    public const MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDED = 'quicklinkgroup-postbottom-extended';
    public const MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER = 'quicklinkgroup-postbottom-extendedvolunteer';
    public const MODULE_QUICKLINKGROUP_POSTEDIT = 'quicklinkgroup-postedit';
    public const MODULE_QUICKLINKGROUP_ADDONSPOSTEDIT = 'quicklinkgroup-addonspostedit';
    public const MODULE_QUICKLINKGROUP_USERCOMPACT = 'quicklinkgroup-usercompact';
    public const MODULE_QUICKLINKGROUP_USER = 'quicklinkgroup-user';
    public const MODULE_QUICKLINKGROUP_USERBOTTOM = 'quicklinkgroup-userbottom';
    public const MODULE_QUICKLINKGROUP_USER_EDITMEMBERS = 'quicklinkgroup-user-editmembers';
    public const MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST = 'quicklinkgroup-updownvoteundoupdownvotepost';
    public const MODULE_QUICKLINKGROUP_TAG = 'quicklinkgroup-tag';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKGROUP_POST],
            [self::class, self::MODULE_QUICKLINKGROUP_POSTBOTTOM],
            [self::class, self::MODULE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER],
            [self::class, self::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDED],
            [self::class, self::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER],
            [self::class, self::MODULE_QUICKLINKGROUP_POSTEDIT],
            [self::class, self::MODULE_QUICKLINKGROUP_ADDONSPOSTEDIT],
            [self::class, self::MODULE_QUICKLINKGROUP_USER],
            [self::class, self::MODULE_QUICKLINKGROUP_USERBOTTOM],
            [self::class, self::MODULE_QUICKLINKGROUP_USERCOMPACT],
            [self::class, self::MODULE_QUICKLINKGROUP_USER_EDITMEMBERS],
            [self::class, self::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST],
            [self::class, self::MODULE_QUICKLINKGROUP_TAG],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_QUICKLINKGROUP_POST:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTSHARE];
                break;

            case self::MODULE_QUICKLINKGROUP_POSTBOTTOM:
            case self::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDED:
                // Allow TPP Debate website to remove the Comments from the post list
                $submodules = array();
                $submodules[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND];
                $submodules[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS];
                if ($module == [self::class, self::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDED]) {
                    $submodules[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES_LINE];
                }
                $submodules = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomQuicklinkGroups:modules',
                    $submodules,
                    $module
                );
                $ret = array_merge(
                    $ret,
                    $submodules
                );
                break;

            case self::MODULE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER:
            case self::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER:
                // Allow TPP Debate website to remove the Comments from the post list
                $submodules = array();
                $submodules[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND];
                $submodules[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS];
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $submodules[] = [PoP_Volunteering_Module_Processor_QuicklinkButtonGroups::class, PoP_Volunteering_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER];
                }
                if ($module == [self::class, self::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER]) {
                    $submodules[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES_LINE];
                }
                $submodules = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomQuicklinkGroups:modules',
                    $submodules,
                    $module
                );
                $ret = array_merge(
                    $ret,
                    $submodules
                );
                break;

            case self::MODULE_QUICKLINKGROUP_POSTEDIT:
                $ret[] = [Wassup_Module_Processor_QuicklinkButtonGroups::class, Wassup_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT];
                $ret[] = [GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::class, GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTVIEW];
                if (defined('PPP_POP_INITIALIZED')) {
                    $ret[] = [GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::class, GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTPREVIEW];
                }
                break;

            case self::MODULE_QUICKLINKGROUP_ADDONSPOSTEDIT:
                $ret[] = [Wassup_Module_Processor_QuicklinkButtonGroups::class, Wassup_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT];
                $ret[] = [GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::class, GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTVIEW];
                if (defined('PPP_POP_INITIALIZED')) {
                    $ret[] = [GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::class, GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTPREVIEW];
                }
                break;

            case self::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:
                // Allow to not show the "Important?" label, since it's too bulky
                if (HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomQuicklinkGroups:updownvotepost:addlabel',
                    false
                )) {
                    $ret[] = [PoP_Module_Processor_CustomCodes::class, PoP_Module_Processor_CustomCodes::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL];
                }
                $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE];
                $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE];
                break;

            case self::MODULE_QUICKLINKGROUP_USER:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERSHARE];
                break;

            case self::MODULE_QUICKLINKGROUP_USERBOTTOM:
                if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER];
                }
                if (defined('PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE')) {
                    $ret[] = [PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE];
                }
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERCONTACTINFO];
                break;

            case self::MODULE_QUICKLINKGROUP_USERCOMPACT:
                if (defined('PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE')) {
                    $ret[] = [PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE];
                }
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERCONTACTINFO];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERSHARE];
                break;

            case self::MODULE_QUICKLINKGROUP_USER_EDITMEMBERS:
                $ret[] = [GD_URE_Module_Processor_QuicklinkButtonGroups::class, GD_URE_Module_Processor_QuicklinkButtonGroups::MODULE_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP];
                break;

            case self::MODULE_QUICKLINKGROUP_TAG:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_TAGSHARE];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:
                $this->appendProp($module, $props, 'class', 'pop-functiongroup');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:
                // Make the level below also a 'btn-group' so it shows inline
                $downlevels = array(
                    self::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST => [PoP_Module_Processor_CustomCodes::class, PoP_Module_Processor_CustomCodes::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL],
                );
                // $this->appendProp($downlevels[$module[1]], $props, 'class', 'btn-group bg-warning');
                $this->appendProp($downlevels[$module[1]], $props, 'class', 'btn-group');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


