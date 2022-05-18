<?php
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\DownvoteCustomPostMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\FollowUserMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\RecommendPostMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\SubscribeToTagMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\UndoDownvoteCustomPostMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\UndoUpvoteCustomPostMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\UnfollowUserMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\UnrecommendPostMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\UnsubscribeFromTagMutationResolverBridge;
use \PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges\UpvoteCustomPostMutationResolverBridge;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\Engine\ComponentProcessors\ObjectIDFromURLParamComponentProcessorTrait;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Module_Processor_ActionDataloads extends PoP_Module_Processor_DataloadsBase
{
    use ObjectIDFromURLParamComponentProcessorTrait;

    public final const MODULE_DATALOADACTION_FOLLOWUSER = 'dataloadaction-followuser';
    public final const MODULE_DATALOADACTION_UNFOLLOWUSER = 'dataloadaction-unfollowuser';
    public final const MODULE_DATALOADACTION_RECOMMENDPOST = 'dataloadaction-recommendpost';
    public final const MODULE_DATALOADACTION_UNRECOMMENDPOST = 'dataloadaction-unrecommendpost';
    public final const MODULE_DATALOADACTION_SUBSCRIBETOTAG = 'dataloadaction-subscribetotag';
    public final const MODULE_DATALOADACTION_UNSUBSCRIBEFROMTAG = 'dataloadaction-unsubscribefromtag';
    public final const MODULE_DATALOADACTION_UPVOTEPOST = 'dataloadaction-upvotepost';
    public final const MODULE_DATALOADACTION_UNDOUPVOTEPOST = 'dataloadaction-undoupvotepost';
    public final const MODULE_DATALOADACTION_DOWNVOTEPOST = 'dataloadaction-downvotepost';
    public final const MODULE_DATALOADACTION_UNDODOWNVOTEPOST = 'dataloadaction-undodownvotepost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOADACTION_FOLLOWUSER],
            [self::class, self::COMPONENT_DATALOADACTION_UNFOLLOWUSER],
            [self::class, self::COMPONENT_DATALOADACTION_RECOMMENDPOST],
            [self::class, self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST],
            [self::class, self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG],
            [self::class, self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG],
            [self::class, self::COMPONENT_DATALOADACTION_UPVOTEPOST],
            [self::class, self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST],
            [self::class, self::COMPONENT_DATALOADACTION_DOWNVOTEPOST],
            [self::class, self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOADACTION_DOWNVOTEPOST => POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST,
            self::COMPONENT_DATALOADACTION_FOLLOWUSER => POP_SOCIALNETWORK_ROUTE_FOLLOWUSER,
            self::COMPONENT_DATALOADACTION_RECOMMENDPOST => POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST,
            self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG => POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG,
            self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST => POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST,
            self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST => POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST,
            self::COMPONENT_DATALOADACTION_UNFOLLOWUSER => POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER,
            self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST => POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST,
            self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG => POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG,
            self::COMPONENT_DATALOADACTION_UPVOTEPOST => POP_SOCIALNETWORK_ROUTE_UPVOTEPOST,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $executers = array(
            self::COMPONENT_DATALOADACTION_FOLLOWUSER => FollowUserMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_UNFOLLOWUSER => UnfollowUserMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_RECOMMENDPOST => RecommendPostMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST => UnrecommendPostMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG => SubscribeToTagMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG => UnsubscribeFromTagMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_UPVOTEPOST => UpvoteCustomPostMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST => UndoUpvoteCustomPostMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_DOWNVOTEPOST => DownvoteCustomPostMutationResolverBridge::class,
            self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST => UndoDownvoteCustomPostMutationResolverBridge::class,
        );
        if ($executer = $executers[$component[1]] ?? null) {
            return $executer;
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    // function getActionexecutionCheckpointConfiguration(array $component, array &$props) {

    //     // The actionexecution is triggered when clicking on the link, not when submitting a form
    //     switch ($component[1]) {

    //         case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
    //         case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
    //         case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
    //         case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
    //         case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
    //         case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
    //         case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
    //         case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
    //         case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
    //         case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:

    //             return null;
    //     }

    //     return parent::getActionexecutionCheckpointConfiguration($component, $props);
    // }

    public function shouldExecuteMutation(array $component, array &$props): bool
    {
        // The actionexecution is triggered when clicking on the link, not when submitting a form
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
            case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
            case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:
                return true;
        }

        return parent::shouldExecuteMutation($component, $props);
    }

    protected function getCheckpointmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
            case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
            case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($component);
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
            case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
            case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:
                return [PoP_Module_Processor_DomainFeedbackMessages::class, PoP_Module_Processor_DomainFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_EMPTY];
        }

        return parent::getFeedbackmessageModule($component);
    }

    public function prepareDataPropertiesAfterMutationExecution(array $component, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($component, $props, $data_properties);

        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
            case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
            case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:
                if ($target_id = App::getMutationResolutionStore()->getResult($this->getComponentMutationResolverBridge($component))) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($target_id);
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $layouts = array(
            self::COMPONENT_DATALOADACTION_FOLLOWUSER => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_FOLLOWSUSERS],
            self::COMPONENT_DATALOADACTION_UNFOLLOWUSER => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_UNFOLLOWSUSERS],
            self::COMPONENT_DATALOADACTION_RECOMMENDPOST => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_RECOMMENDSPOSTS],
            self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_UNRECOMMENDSPOSTS],
            self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_SUBSCRIBESTOTAGS],
            self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_UNSUBSCRIBESFROMTAGS],
            self::COMPONENT_DATALOADACTION_UPVOTEPOST => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_UPVOTESPOSTS],
            self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_UNDOUPVOTESPOSTS],
            self::COMPONENT_DATALOADACTION_DOWNVOTEPOST => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_DOWNVOTESPOSTS],
            self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::COMPONENT_CONTENT_UNDODOWNVOTESPOSTS],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
            case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
            case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
                return $this->getObjectIDFromURLParam($component, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    protected function getObjectIDParamName(array $component, array &$props, array &$data_properties): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:
                return \PoPCMSSchema\Posts\Constants\InputNames::POST_ID;
            case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
            case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
                return \PoPCMSSchema\Tags\Constants\InputNames::TAG_ID;
            case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
                return \PoPCMSSchema\Users\Constants\InputNames::USER_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);

            case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();

            case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
            case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $towhats = array(
            self::COMPONENT_DATALOADACTION_FOLLOWUSER => TranslationAPIFacade::getInstance()->__('follow users', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_UNFOLLOWUSER => TranslationAPIFacade::getInstance()->__('stop following users', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_RECOMMENDPOST => TranslationAPIFacade::getInstance()->__('recommend posts', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST => TranslationAPIFacade::getInstance()->__('stop recommending posts', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG => TranslationAPIFacade::getInstance()->__('subscribe to tags/topics', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG => TranslationAPIFacade::getInstance()->__('unsubscribe from tags/topics', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_UPVOTEPOST => TranslationAPIFacade::getInstance()->__('up-vote posts', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST => TranslationAPIFacade::getInstance()->__('stop up-voting posts', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_DOWNVOTEPOST => TranslationAPIFacade::getInstance()->__('down-vote posts', 'pop-coreprocessors'),
            self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST => TranslationAPIFacade::getInstance()->__('stop down-voting posts', 'pop-coreprocessors'),
        );
        if ($towhat = $towhats[$component[1]] ?? null) {
            $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', $towhat);
        }

        // Remove the success/error headers
        $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_EMPTY], $props, 'error-header', '');
        $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_EMPTY], $props, 'success-header', '');

        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_FOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_UNFOLLOWUSER:
            case self::COMPONENT_DATALOADACTION_RECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_UNRECOMMENDPOST:
            case self::COMPONENT_DATALOADACTION_SUBSCRIBETOTAG:
            case self::COMPONENT_DATALOADACTION_UNSUBSCRIBEFROMTAG:
            case self::COMPONENT_DATALOADACTION_UPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDOUPVOTEPOST:
            case self::COMPONENT_DATALOADACTION_DOWNVOTEPOST:
            case self::COMPONENT_DATALOADACTION_UNDODOWNVOTEPOST:
                $this->appendProp($component, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



