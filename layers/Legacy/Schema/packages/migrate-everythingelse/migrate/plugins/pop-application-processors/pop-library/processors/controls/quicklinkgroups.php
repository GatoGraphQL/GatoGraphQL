<?php

class PoP_Module_Processor_CustomQuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_QUICKLINKGROUP_POST = 'quicklinkgroup-post';
    public final const COMPONENT_QUICKLINKGROUP_POSTBOTTOM = 'quicklinkgroup-postbottom';
    public final const COMPONENT_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER = 'quicklinkgroup-postbottomvolunteer';
    public final const COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDED = 'quicklinkgroup-postbottom-extended';
    public final const COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER = 'quicklinkgroup-postbottom-extendedvolunteer';
    public final const COMPONENT_QUICKLINKGROUP_POSTEDIT = 'quicklinkgroup-postedit';
    public final const COMPONENT_QUICKLINKGROUP_ADDONSPOSTEDIT = 'quicklinkgroup-addonspostedit';
    public final const COMPONENT_QUICKLINKGROUP_USERCOMPACT = 'quicklinkgroup-usercompact';
    public final const COMPONENT_QUICKLINKGROUP_USER = 'quicklinkgroup-user';
    public final const COMPONENT_QUICKLINKGROUP_USERBOTTOM = 'quicklinkgroup-userbottom';
    public final const COMPONENT_QUICKLINKGROUP_USER_EDITMEMBERS = 'quicklinkgroup-user-editmembers';
    public final const COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST = 'quicklinkgroup-updownvoteundoupdownvotepost';
    public final const COMPONENT_QUICKLINKGROUP_TAG = 'quicklinkgroup-tag';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_QUICKLINKGROUP_POST,
            self::COMPONENT_QUICKLINKGROUP_POSTBOTTOM,
            self::COMPONENT_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER,
            self::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDED,
            self::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER,
            self::COMPONENT_QUICKLINKGROUP_POSTEDIT,
            self::COMPONENT_QUICKLINKGROUP_ADDONSPOSTEDIT,
            self::COMPONENT_QUICKLINKGROUP_USER,
            self::COMPONENT_QUICKLINKGROUP_USERBOTTOM,
            self::COMPONENT_QUICKLINKGROUP_USERCOMPACT,
            self::COMPONENT_QUICKLINKGROUP_USER_EDITMEMBERS,
            self::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST,
            self::COMPONENT_QUICKLINKGROUP_TAG,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_QUICKLINKGROUP_POST:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTSHARE];
                break;

            case self::COMPONENT_QUICKLINKGROUP_POSTBOTTOM:
            case self::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDED:
                // Allow TPP Debate website to remove the Comments from the post list
                $subcomponents = array();
                $subcomponents[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND];
                $subcomponents[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS];
                if ($component == self::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDED) {
                    $subcomponents[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE];
                }
                $subcomponents = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomQuicklinkGroups:modules',
                    $subcomponents,
                    $component
                );
                $ret = array_merge(
                    $ret,
                    $subcomponents
                );
                break;

            case self::COMPONENT_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER:
            case self::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER:
                // Allow TPP Debate website to remove the Comments from the post list
                $subcomponents = array();
                $subcomponents[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND];
                $subcomponents[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS];
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $subcomponents[] = [PoP_Volunteering_Module_Processor_QuicklinkButtonGroups::class, PoP_Volunteering_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTVOLUNTEER];
                }
                if ($component == self::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER) {
                    $subcomponents[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE];
                }
                $subcomponents = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomQuicklinkGroups:modules',
                    $subcomponents,
                    $component
                );
                $ret = array_merge(
                    $ret,
                    $subcomponents
                );
                break;

            case self::COMPONENT_QUICKLINKGROUP_POSTEDIT:
                $ret[] = [Wassup_Module_Processor_QuicklinkButtonGroups::class, Wassup_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT];
                $ret[] = [GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::class, GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTVIEW];
                if (defined('PPP_POP_INITIALIZED')) {
                    $ret[] = [GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::class, GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTPREVIEW];
                }
                break;

            case self::COMPONENT_QUICKLINKGROUP_ADDONSPOSTEDIT:
                $ret[] = [Wassup_Module_Processor_QuicklinkButtonGroups::class, Wassup_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT];
                $ret[] = [GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::class, GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTVIEW];
                if (defined('PPP_POP_INITIALIZED')) {
                    $ret[] = [GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::class, GD_ContentCreation_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTPREVIEW];
                }
                break;

            case self::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:
                // Allow to not show the "Important?" label, since it's too bulky
                if (\PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomQuicklinkGroups:updownvotepost:addlabel',
                    false
                )) {
                    $ret[] = [PoP_Module_Processor_CustomCodes::class, PoP_Module_Processor_CustomCodes::COMPONENT_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL];
                }
                $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE];
                $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE];
                break;

            case self::COMPONENT_QUICKLINKGROUP_USER:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERSHARE];
                break;

            case self::COMPONENT_QUICKLINKGROUP_USERBOTTOM:
                if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER];
                }
                if (defined('PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERSENDMESSAGE')) {
                    $ret[] = [PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERSENDMESSAGE];
                }
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERCONTACTINFO];
                break;

            case self::COMPONENT_QUICKLINKGROUP_USERCOMPACT:
                if (defined('PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERSENDMESSAGE')) {
                    $ret[] = [PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, PoP_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERSENDMESSAGE];
                }
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERCONTACTINFO];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERSHARE];
                break;

            case self::COMPONENT_QUICKLINKGROUP_USER_EDITMEMBERS:
                $ret[] = [GD_URE_Module_Processor_QuicklinkButtonGroups::class, GD_URE_Module_Processor_QuicklinkButtonGroups::COMPONENT_URE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP];
                break;

            case self::COMPONENT_QUICKLINKGROUP_TAG:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_TAGSHARE];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:
                $this->appendProp($component, $props, 'class', 'pop-functiongroup');
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:
                // Make the level below also a 'btn-group' so it shows inline
                $downlevels = array(
                    self::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST => [PoP_Module_Processor_CustomCodes::class, PoP_Module_Processor_CustomCodes::COMPONENT_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL],
                );
                // $this->appendProp($downlevels[$component->name], $props, 'class', 'btn-group bg-warning');
                $this->appendProp($downlevels[$component->name], $props, 'class', 'btn-group');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


