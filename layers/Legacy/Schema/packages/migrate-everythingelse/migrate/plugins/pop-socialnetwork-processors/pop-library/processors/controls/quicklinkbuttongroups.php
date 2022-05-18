<?php

class GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER = 'quicklinkbuttongroup-userfollowunfollowuser';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND = 'quicklinkbuttongroup-postrecommendunrecommend';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE = 'quicklinkbuttongroup-postupvoteundoupvote';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE = 'quicklinkbuttongroup-postdownvoteundodownvote';
    public final const MODULE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM = 'quicklinkbuttongroup-tagsubscribetounsubscribefrom';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_FOLLOWUSER_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_RECOMMENDPOST_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_UPVOTEPOST_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW];
                break;
        }
        
        return $ret;
    }
}


