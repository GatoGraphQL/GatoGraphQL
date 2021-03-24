<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\MutationResolution\MutationResolutionManagerFacade;
use PoPSitesWassup\CommentMutations\MutationResolverBridges\AddCommentToCustomPostMutationResolverBridge;

class PoP_Module_Processor_CommentsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public const MODULE_DATALOAD_COMMENTS_SCROLL = 'dataload-comments-scroll';
    public const MODULE_DATALOAD_ADDCOMMENT = 'dataload-addcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_COMMENTS_SCROLL],
            [self::class, self::MODULE_DATALOAD_ADDCOMMENT],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_ADDCOMMENT => POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            self::MODULE_DATALOAD_COMMENTS_SCROLL => POP_BLOG_ROUTE_COMMENTS,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return AddCommentToCustomPostMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
                return GD_DataLoad_QueryInputOutputHandler_CommentList::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CommentFilters::class, PoP_Module_Processor_CommentFilters::MODULE_FILTER_COMMENTS];
        }

        return parent::getFilterSubmodule($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return [PoP_Module_Processor_CommentsFeedbackMessages::class, PoP_Module_Processor_CommentsFeedbackMessages::MODULE_FEEDBACKMESSAGE_ADDCOMMENT];

            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
                return [PoP_Module_Processor_CommentsFeedbackMessages::class, PoP_Module_Processor_CommentsFeedbackMessages::MODULE_FEEDBACKMESSAGE_COMMENTS];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getCheckpointmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
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

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_COMMENTS_SCROLL:
            case self::MODULE_DATALOAD_ADDCOMMENT:
                return \PoPSchema\Comments\CommentTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function prepareDataPropertiesAfterActionexecution(array $module, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterActionexecution($module, $props, $data_properties);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                $gd_dataload_actionexecution_manager = MutationResolutionManagerFacade::getInstance();
                if ($comment_id = $gd_dataload_actionexecution_manager->getResult(AddCommentToCustomPostMutationResolverBridge::class)) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($comment_id);
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_ADDCOMMENT:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('add a comment', 'poptheme-wassup'));

                $this->appendProp([[PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::MODULE_SCROLL_COMMENTS_ADD]], $props, 'class', 'hidden');

                // // Do not show the labels in the form
                // $this->appendProp([PoP_Module_Processor_CommentsForms::class, PoP_Module_Processor_CommentsForms::MODULE_FORM_ADDCOMMENT], $props, 'class', 'nolabel');

                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



