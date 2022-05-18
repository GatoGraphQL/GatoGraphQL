<?php

class GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER = 'quicklinkbuttongroup-userfollowunfollowuser';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND = 'quicklinkbuttongroup-postrecommendunrecommend';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE = 'quicklinkbuttongroup-postupvoteundoupvote';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE = 'quicklinkbuttongroup-postdownvoteundodownvote';
    public final const MODULE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM = 'quicklinkbuttongroup-tagsubscribetounsubscribefrom';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER],
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND],
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE],
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE],
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_FOLLOWUSER_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_UNFOLLOWUSER_PREVIEW];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_RECOMMENDPOST_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_UNRECOMMENDPOST_PREVIEW];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_UPVOTEPOST_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_UNDOUPVOTEPOST_PREVIEW];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_DOWNVOTEPOST_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_UNDODOWNVOTEPOST_PREVIEW];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM:
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_SUBSCRIBETOTAG_PREVIEW];
                $ret[] = [PoP_Module_Processor_FunctionButtons::class, PoP_Module_Processor_FunctionButtons::COMPONENT_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW];
                break;
        }
        
        return $ret;
    }
}


