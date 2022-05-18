<?php

use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;
use PoPSitesWassup\StanceMutations\MutationResolverBridges\CreateOrUpdateStanceMutationResolverBridge;
use PoPSitesWassup\StanceMutations\MutationResolverBridges\CreateStanceMutationResolverBridge;
use PoPSitesWassup\StanceMutations\MutationResolverBridges\UpdateStanceMutationResolverBridge;

class UserStance_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const COMPONENT_DATALOAD_STANCE_UPDATE = 'dataload-stance-update';
    public final const COMPONENT_DATALOAD_STANCE_CREATE = 'dataload-stance-create';
    public final const COMPONENT_DATALOAD_STANCE_CREATEORUPDATE = 'dataload-stance-createorupdate';
    public final const COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE = 'dataload-singlepoststance-createorupdate';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_STANCE_UPDATE],
            [self::class, self::COMPONENT_DATALOAD_STANCE_CREATE],
            [self::class, self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE],
            [self::class, self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::COMPONENT_DATALOAD_STANCE_CREATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::COMPONENT_DATALOAD_STANCE_UPDATE => POP_USERSTANCE_ROUTE_EDITSTANCE,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function getFeedbackmessageModule(array $component)
    {
        $feedbacks = array(
            self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE => [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_CREATECONTENT],
            self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE => [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_CREATECONTENT],
        );

        if ($feedback = $feedbacks[$component[1]] ?? null) {
            return $feedback;
        }

        return parent::getFeedbackmessageModule($component);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
                return $this->instanceManager->getInstance(CreateStanceMutationResolverBridge::class);
            case self::COMPONENT_DATALOAD_STANCE_UPDATE:
                return $this->instanceManager->getInstance(UpdateStanceMutationResolverBridge::class);
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return $this->instanceManager->getInstance(CreateOrUpdateStanceMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_UPDATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_CreateUpdatePostForms::class, UserStance_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_STANCE];
                break;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $this->addJsmethod($ret, 'deleteBlockFeedbackValueOnUserLoggedInOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedInOut');
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                if (!\PoP\Root\App::getState('is-user-logged-in')) {
                    return [];
                }
                $query = array(
                    'status' => array(Status::PUBLISHED, Status::DRAFT),
                    'authors' => [\PoP\Root\App::getState('current-user-id')],
                );
                if ($component[1] == self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE) {
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsGeneralstances($query);
                }
                elseif ($component[1] == self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE) {
                    $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $post_id);
                }

                // Stances are unique, just 1 per person/article.
                // Check if there is a Stance for the given post.
                $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
                if ($stances = $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return array($stances[0]);
                }
                return [];
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return $this->instanceManager->getInstance(StanceObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_AddPost::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret['executeFetchBlockSuccess']['processblock-ifhasdata'] = true;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $name = PoP_UserStance_PostNameUtils::getNameUc();
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                break;

            case self::COMPONENT_DATALOAD_STANCE_UPDATE:
                $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



