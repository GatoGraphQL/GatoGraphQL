<?php

class PoP_Module_Processor_FunctionsBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_FOLLOWUSER = 'block-followuser';
    public final const MODULE_BLOCK_UNFOLLOWUSER = 'block-unfollowuser';
    public final const MODULE_BLOCK_RECOMMENDPOST = 'block-recommendpost';
    public final const MODULE_BLOCK_UNRECOMMENDPOST = 'block-unrecommendpost';
    public final const MODULE_BLOCK_SUBSCRIBETOTAG = 'block-subscribetotag';
    public final const MODULE_BLOCK_UNSUBSCRIBEFROMTAG = 'block-unsubscribefromtag';
    public final const MODULE_BLOCK_UPVOTEPOST = 'block-upvotepost';
    public final const MODULE_BLOCK_UNDOUPVOTEPOST = 'block-undoupvotepost';
    public final const MODULE_BLOCK_DOWNVOTEPOST = 'block-downvotepost';
    public final const MODULE_BLOCK_UNDODOWNVOTEPOST = 'block-undodownvotepost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_FOLLOWUSER],
            [self::class, self::MODULE_BLOCK_UNFOLLOWUSER],
            [self::class, self::MODULE_BLOCK_RECOMMENDPOST],
            [self::class, self::MODULE_BLOCK_UNRECOMMENDPOST],
            [self::class, self::MODULE_BLOCK_SUBSCRIBETOTAG],
            [self::class, self::MODULE_BLOCK_UNSUBSCRIBEFROMTAG],
            [self::class, self::MODULE_BLOCK_UPVOTEPOST],
            [self::class, self::MODULE_BLOCK_UNDOUPVOTEPOST],
            [self::class, self::MODULE_BLOCK_DOWNVOTEPOST],
            [self::class, self::MODULE_BLOCK_UNDODOWNVOTEPOST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_DOWNVOTEPOST => POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST,
            self::MODULE_BLOCK_FOLLOWUSER => POP_SOCIALNETWORK_ROUTE_FOLLOWUSER,
            self::MODULE_BLOCK_RECOMMENDPOST => POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST,
            self::MODULE_BLOCK_SUBSCRIBETOTAG => POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG,
            self::MODULE_BLOCK_UNDODOWNVOTEPOST => POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST,
            self::MODULE_BLOCK_UNDOUPVOTEPOST => POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST,
            self::MODULE_BLOCK_UNFOLLOWUSER => POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER,
            self::MODULE_BLOCK_UNRECOMMENDPOST => POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST,
            self::MODULE_BLOCK_UNSUBSCRIBEFROMTAG => POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG,
            self::MODULE_BLOCK_UPVOTEPOST => POP_SOCIALNETWORK_ROUTE_UPVOTEPOST,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $layouts = array(
            self::MODULE_BLOCK_FOLLOWUSER => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_FOLLOWUSER],
            self::MODULE_BLOCK_UNFOLLOWUSER => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_UNFOLLOWUSER],
            self::MODULE_BLOCK_RECOMMENDPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_RECOMMENDPOST],
            self::MODULE_BLOCK_UNRECOMMENDPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_UNRECOMMENDPOST],
            self::MODULE_BLOCK_SUBSCRIBETOTAG => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_SUBSCRIBETOTAG],
            self::MODULE_BLOCK_UNSUBSCRIBEFROMTAG => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_UNSUBSCRIBEFROMTAG],
            self::MODULE_BLOCK_UPVOTEPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_UPVOTEPOST],
            self::MODULE_BLOCK_UNDOUPVOTEPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_UNDOUPVOTEPOST],
            self::MODULE_BLOCK_DOWNVOTEPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_DOWNVOTEPOST],
            self::MODULE_BLOCK_UNDODOWNVOTEPOST => [PoP_Module_Processor_ActionDataloads::class, PoP_Module_Processor_ActionDataloads::MODULE_DATALOADACTION_UNDODOWNVOTEPOST],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_FOLLOWUSER:
            case self::MODULE_BLOCK_UNFOLLOWUSER:
            case self::MODULE_BLOCK_RECOMMENDPOST:
            case self::MODULE_BLOCK_UNRECOMMENDPOST:
            case self::MODULE_BLOCK_SUBSCRIBETOTAG:
            case self::MODULE_BLOCK_UNSUBSCRIBEFROMTAG:
            case self::MODULE_BLOCK_UPVOTEPOST:
            case self::MODULE_BLOCK_UNDOUPVOTEPOST:
            case self::MODULE_BLOCK_DOWNVOTEPOST:
            case self::MODULE_BLOCK_UNDODOWNVOTEPOST:
                // Close the alerts after a few seconds
                $this->mergeJsmethodsProp([PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::class, PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY], $props, array('alertCloseOnTimeout'));
                $this->mergeProp(
                    [PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::class, PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY],
                    $props,
                    'params',
                    array(
                        'data-closetime' => 3500,
                    )
                );

                $this->mergeJsmethodsProp([GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN], $props, array('alertCloseOnTimeout'));
                $this->mergeProp(
                    [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN],
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
            case self::MODULE_BLOCK_FOLLOWUSER:
            case self::MODULE_BLOCK_UNFOLLOWUSER:
            case self::MODULE_BLOCK_RECOMMENDPOST:
            case self::MODULE_BLOCK_UNRECOMMENDPOST:
            case self::MODULE_BLOCK_SUBSCRIBETOTAG:
            case self::MODULE_BLOCK_UNSUBSCRIBEFROMTAG:
            case self::MODULE_BLOCK_UPVOTEPOST:
            case self::MODULE_BLOCK_UNDOUPVOTEPOST:
            case self::MODULE_BLOCK_DOWNVOTEPOST:
            case self::MODULE_BLOCK_UNDODOWNVOTEPOST:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($component, $props, 'resourceloader', 'functionalblock');
                $this->appendProp($component, $props, 'class', 'pop-functionalblock');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



