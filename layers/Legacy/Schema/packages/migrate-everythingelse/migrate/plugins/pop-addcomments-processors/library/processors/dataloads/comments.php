<?php

use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSitesWassup\CommentMutations\MutationResolverBridges\AddCommentToCustomPostMutationResolverBridge;

class PoP_Module_Processor_CommentsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_COMMENTS_SCROLL = 'dataload-comments-scroll';
    public final const MODULE_DATALOAD_ADDCOMMENT = 'dataload-addcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_COMMENTS_SCROLL],
            [self::class, self::MODULE_DATALOAD_ADDCOMMENT],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_ADDCOMMENT => POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            self::MODULE_DATALOAD_COMMENTS_SCROLL => POP_BLOG_ROUTE_COMMENTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_CommentList::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CommentFilters::class, PoP_Module_Processor_CommentFilters::MODULE_FILTER_COMMENTS];
        }

        return parent::getFilterSubmodule($component);
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return [PoP_Module_Processor_CommentsFeedbackMessages::class, PoP_Module_Processor_CommentsFeedbackMessages::MODULE_FEEDBACKMESSAGE_ADDCOMMENT];

            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CommentsFeedbackMessages::class, PoP_Module_Processor_CommentsFeedbackMessages::MODULE_FEEDBACKMESSAGE_COMMENTS];
        }

        return parent::getFeedbackmessageModule($component);
    }

    protected function getCheckpointmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::MODULE_SCROLL_COMMENTS_LIST];
                break;

            case self::MODULE_DATALOAD_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_CommentsForms::class, PoP_Module_Processor_CommentsForms::MODULE_FORM_ADDCOMMENT];
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::MODULE_SCROLL_COMMENTS_ADD];
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function prepareDataPropertiesAfterMutationExecution(array $component, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($component, $props, $data_properties);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
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
            case self::MODULE_DATALOAD_ADDCOMMENT:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('add a comment', 'poptheme-wassup'));

                $this->appendProp([[PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::MODULE_SCROLL_COMMENTS_ADD]], $props, 'class', 'hidden');

                // // Do not show the labels in the form
                // $this->appendProp([PoP_Module_Processor_CommentsForms::class, PoP_Module_Processor_CommentsForms::MODULE_FORM_ADDCOMMENT], $props, 'class', 'nolabel');

                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



