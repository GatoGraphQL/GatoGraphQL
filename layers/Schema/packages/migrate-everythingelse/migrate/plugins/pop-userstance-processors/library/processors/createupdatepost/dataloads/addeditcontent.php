<?php

use PoPSchema\CustomPosts\Types\Status;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Stances\TypeResolvers\StanceTypeResolver;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSitesWassup\StanceMutations\MutationResolverBridges\CreateStanceMutationResolverBridge;
use PoPSitesWassup\StanceMutations\MutationResolverBridges\UpdateStanceMutationResolverBridge;
use PoPSitesWassup\StanceMutations\MutationResolverBridges\CreateOrUpdateStanceMutationResolverBridge;

class UserStance_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public const MODULE_DATALOAD_STANCE_UPDATE = 'dataload-stance-update';
    public const MODULE_DATALOAD_STANCE_CREATE = 'dataload-stance-create';
    public const MODULE_DATALOAD_STANCE_CREATEORUPDATE = 'dataload-stance-createorupdate';
    public const MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE = 'dataload-singlepoststance-createorupdate';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_STANCE_UPDATE],
            [self::class, self::MODULE_DATALOAD_STANCE_CREATE],
            [self::class, self::MODULE_DATALOAD_STANCE_CREATEORUPDATE],
            [self::class, self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::MODULE_DATALOAD_STANCE_CREATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::MODULE_DATALOAD_STANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::MODULE_DATALOAD_STANCE_UPDATE => POP_USERSTANCE_ROUTE_EDITSTANCE,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_STANCE_CREATE:
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
                return GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        $feedbacks = array(
            self::MODULE_DATALOAD_STANCE_CREATEORUPDATE => [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_CREATECONTENT],
            self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE => [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_CREATECONTENT],
        );

        if ($feedback = $feedbacks[$module[1]] ?? null) {
            return $feedback;
        }

        return parent::getFeedbackmessageModule($module);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_CREATE:
                return CreateStanceMutationResolverBridge::class;
            case self::MODULE_DATALOAD_STANCE_UPDATE:
                return UpdateStanceMutationResolverBridge::class;
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return CreateOrUpdateStanceMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_UPDATE:
            case self::MODULE_DATALOAD_STANCE_CREATE:
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_CreateUpdatePostForms::class, UserStance_Module_Processor_CreateUpdatePostForms::MODULE_FORM_STANCE];
                break;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $this->addJsmethod($ret, 'deleteBlockFeedbackValueOnUserLoggedInOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedInOut');
                break;
        }

        return $ret;
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $vars = ApplicationState::getVars();
                if (!$vars['global-userstate']['is-user-logged-in']) {
                    return [];
                }
                $query = array(
                    'status' => array(Status::PUBLISHED, Status::DRAFT),
                    'authors' => [$vars['global-userstate']['current-user-id']],
                );
                if ($module[1] == self::MODULE_DATALOAD_STANCE_CREATEORUPDATE) {
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsGeneralstances($query);
                }
                elseif ($module[1] == self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE) {
                    $post_id = $vars['routing-state']['queried-object-id'];
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $post_id);
                }

                // Stances are unique, just 1 per person/article.
                // Check if there is a Stance for the given post.
                $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
                if ($stances = $customPostTypeAPI->getCustomPosts($query, ['return-type' => ReturnTypes::IDS])) {
                    return array($stances[0]);
                }
                return [];
        }
        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_CREATE:
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return StanceTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return GD_DataLoad_QueryInputOutputHandler_AddPost::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret['executeFetchBlockSuccess']['processblock-ifhasdata'] = true;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        $name = PoP_UserStance_PostNameUtils::getNameUc();
        switch ($module[1]) {
            case self::MODULE_DATALOAD_STANCE_CREATE:
            case self::MODULE_DATALOAD_STANCE_CREATEORUPDATE:
            case self::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                break;

            case self::MODULE_DATALOAD_STANCE_UPDATE:
                $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



