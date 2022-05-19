<?php

use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSitesWassup\CommentMutations\MutationResolverBridges\AddCommentToCustomPostMutationResolverBridge;

class PoP_Module_Processor_CommentsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_COMMENTS_SCROLL = 'dataload-comments-scroll';
    public final const COMPONENT_DATALOAD_ADDCOMMENT = 'dataload-addcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_COMMENTS_SCROLL],
            [self::class, self::COMPONENT_DATALOAD_ADDCOMMENT],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_ADDCOMMENT => POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            self::COMPONENT_DATALOAD_COMMENTS_SCROLL => POP_BLOG_ROUTE_COMMENTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ADDCOMMENT:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ADDCOMMENT:
                return $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_COMMENTS_SCROLL:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_CommentList::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubcomponent(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CommentFilters::class, PoP_Module_Processor_CommentFilters::COMPONENT_FILTER_COMMENTS];
        }

        return parent::getFilterSubcomponent($component);
    }

    protected function getFeedbackMessageComponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ADDCOMMENT:
                return [PoP_Module_Processor_CommentsFeedbackMessages::class, PoP_Module_Processor_CommentsFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_ADDCOMMENT];

            case self::COMPONENT_DATALOAD_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CommentsFeedbackMessages::class, PoP_Module_Processor_CommentsFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_COMMENTS];
        }

        return parent::getFeedbackMessageComponent($component);
    }

    protected function getCheckpointMessageComponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ADDCOMMENT:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointMessageComponent($component);
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_COMMENTS_SCROLL:
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::COMPONENT_SCROLL_COMMENTS_LIST];
                break;

            case self::COMPONENT_DATALOAD_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_CommentsForms::class, PoP_Module_Processor_CommentsForms::COMPONENT_FORM_ADDCOMMENT];
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::COMPONENT_SCROLL_COMMENTS_ADD];
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_COMMENTS_SCROLL:
            case self::COMPONENT_DATALOAD_ADDCOMMENT:
                return $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function prepareDataPropertiesAfterMutationExecution(array $component, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($component, $props, $data_properties);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ADDCOMMENT:
                if ($comment_id = App::getMutationResolutionStore()->getResult($this->instanceManager->getInstance(AddCommentToCustomPostMutationResolverBridge::class))) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($comment_id);
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_ADDCOMMENT:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('add a comment', 'poptheme-wassup'));

                $this->appendProp([[PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::COMPONENT_SCROLL_COMMENTS_ADD]], $props, 'class', 'hidden');

                // // Do not show the labels in the form
                // $this->appendProp([PoP_Module_Processor_CommentsForms::class, PoP_Module_Processor_CommentsForms::COMPONENT_FORM_ADDCOMMENT], $props, 'class', 'nolabel');

                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



