<?php

class PoP_Module_Processor_FunctionsBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_FOLLOWUSER = 'block-followuser';
    public final const COMPONENT_BLOCK_UNFOLLOWUSER = 'block-unfollowuser';
    public final const COMPONENT_BLOCK_RECOMMENDPOST = 'block-recommendpost';
    public final const COMPONENT_BLOCK_UNRECOMMENDPOST = 'block-unrecommendpost';
    public final const COMPONENT_BLOCK_SUBSCRIBETOTAG = 'block-subscribetotag';
    public final const COMPONENT_BLOCK_UNSUBSCRIBEFROMTAG = 'block-unsubscribefromtag';
    public final const COMPONENT_BLOCK_UPVOTEPOST = 'block-upvotepost';
    public final const COMPONENT_BLOCK_UNDOUPVOTEPOST = 'block-undoupvotepost';
    public final const COMPONENT_BLOCK_DOWNVOTEPOST = 'block-downvotepost';
    public final const COMPONENT_BLOCK_UNDODOWNVOTEPOST = 'block-undodownvotepost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_FOLLOWUSER],
            [self::class, self::COMPONENT_BLOCK_UNFOLLOWUSER],
            [self::class, self::COMPONENT_BLOCK_RECOMMENDPOST],
            [self::class, self::COMPONENT_BLOCK_UNRECOMMENDPOST],
            [self::class, self::COMPONENT_BLOCK_SUBSCRIBETOTAG],
            [self::class, self::COMPONENT_BLOCK_UNSUBSCRIBEFROMTAG],
            [self::class, self::COMPONENT_BLOCK_UPVOTEPOST],
            [self::class, self::COMPONENT_BLOCK_UNDOUPVOTEPOST],
            [self::class, self::COMPONENT_BLOCK_DOWNVOTEPOST],
            [self::class, self::COMPONENT_BLOCK_UNDODOWNVOTEPOST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_DOWNVOTEPOST => POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST,
            self::COMPONENT_BLOCK_FOLLOWUSER => POP_SOCIALNETWORK_ROUTE_FOLLOWUSER,
            self::COMPONENT_BLOCK_RECOMMENDPOST => POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST,
            self::COMPONENT_BLOCK_SUBSCRIBETOTAG => POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG,
            self::COMPONENT_BLOCK_UNDODOWNVOTEPOST => POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST,
            self::COMPONENT_BLOCK_UNDOUPVOTEPOST => POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST,
            self::COMPONENT_BLOCK_UNFOLLOWUSER => POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER,
            self::COMPONENT_BLOCK_UNRECOMMENDPOST => POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST,
            self::COMPONENT_BLOCK_UNSUBSCRIBEFROMTAG => POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG,
            self::COMPONENT_BLOCK_UPVOTEPOST => POP_SOCIALNETWORK_ROUTE_UPVOTEPOST,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $layouts = array(
            self::COMPONENT_BLOCK_FOLLOWUSER => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_FOLLOWUSER],
            self::COMPONENT_BLOCK_UNFOLLOWUSER => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_UNFOLLOWUSER],
            self::COMPONENT_BLOCK_RECOMMENDPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_RECOMMENDPOST],
            self::COMPONENT_BLOCK_UNRECOMMENDPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_UNRECOMMENDPOST],
            self::COMPONENT_BLOCK_SUBSCRIBETOTAG => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG],
            self::COMPONENT_BLOCK_UNSUBSCRIBEFROMTAG => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG],
            self::COMPONENT_BLOCK_UPVOTEPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_UPVOTEPOST],
            self::COMPONENT_BLOCK_UNDOUPVOTEPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST],
            self::COMPONENT_BLOCK_DOWNVOTEPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_DOWNVOTEPOST],
            self::COMPONENT_BLOCK_UNDODOWNVOTEPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_FOLLOWUSER:
            case self::COMPONENT_BLOCK_UNFOLLOWUSER:
            case self::COMPONENT_BLOCK_RECOMMENDPOST:
            case self::COMPONENT_BLOCK_UNRECOMMENDPOST:
            case self::COMPONENT_BLOCK_SUBSCRIBETOTAG:
            case self::COMPONENT_BLOCK_UNSUBSCRIBEFROMTAG:
            case self::COMPONENT_BLOCK_UPVOTEPOST:
            case self::COMPONENT_BLOCK_UNDOUPVOTEPOST:
            case self::COMPONENT_BLOCK_DOWNVOTEPOST:
            case self::COMPONENT_BLOCK_UNDODOWNVOTEPOST:
                // Close the alerts after a few seconds
                $this->mergeJsmethodsProp([PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::class, PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY], $props, array('alertCloseOnTimeout'));
                $this->mergeProp(
                    [PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::class, PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY],
                    $props,
                    'params',
                    array(
                        'data-closetime' => 3500,
                    )
                );

                $this->mergeJsmethodsProp([GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN], $props, array('alertCloseOnTimeout'));
                $this->mergeProp(
                    [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN],
                    $props,
                    'params',
                    array(
                        'data-closetime' => 3500,
                    )
                );
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_FOLLOWUSER:
            case self::COMPONENT_BLOCK_UNFOLLOWUSER:
            case self::COMPONENT_BLOCK_RECOMMENDPOST:
            case self::COMPONENT_BLOCK_UNRECOMMENDPOST:
            case self::COMPONENT_BLOCK_SUBSCRIBETOTAG:
            case self::COMPONENT_BLOCK_UNSUBSCRIBEFROMTAG:
            case self::COMPONENT_BLOCK_UPVOTEPOST:
            case self::COMPONENT_BLOCK_UNDOUPVOTEPOST:
            case self::COMPONENT_BLOCK_DOWNVOTEPOST:
            case self::COMPONENT_BLOCK_UNDODOWNVOTEPOST:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'functionalblock');
                $this->appendProp($component, $props, 'class', 'pop-functionalblock');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



