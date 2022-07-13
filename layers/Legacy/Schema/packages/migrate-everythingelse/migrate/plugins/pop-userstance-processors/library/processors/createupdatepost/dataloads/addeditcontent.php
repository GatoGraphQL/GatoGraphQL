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

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_STANCE_UPDATE,
            self::COMPONENT_DATALOAD_STANCE_CREATE,
            self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE,
            self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::COMPONENT_DATALOAD_STANCE_CREATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::COMPONENT_DATALOAD_STANCE_UPDATE => POP_USERSTANCE_ROUTE_EDITSTANCE,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function getFeedbackMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        $feedbacks = array(
            self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE => [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_CREATECONTENT],
            self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE => [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_CREATECONTENT],
        );

        if ($feedback = $feedbacks[$component->name] ?? null) {
            return $feedback;
        }

        return parent::getFeedbackMessageComponent($component);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
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

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_STANCE_UPDATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_CreateUpdatePostForms::class, UserStance_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_STANCE];
                break;
        }

        return $ret;
    }

    protected function isCreate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_STANCE_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $this->addJsmethod($ret, 'deleteBlockFeedbackValueOnUserLoggedInOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedInOut');
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string|int|array
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                if (!\PoP\Root\App::getState('is-user-logged-in')) {
                    return [];
                }
                $query = array(
                    'status' => array(Status::PUBLISHED, Status::DRAFT),
                    'authors' => [\PoP\Root\App::getState('current-user-id')],
                );
                if ($component->name == self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE) {
                    UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsGeneralstances($query);
                }
                elseif ($component->name == self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE) {
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

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_STANCE_CREATE:
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return $this->instanceManager->getInstance(StanceObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(\PoP\ComponentModel\Component\Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_AddPost::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE:
            case self::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret['executeFetchBlockSuccess']['processblock-ifhasdata'] = true;
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $name = PoP_UserStance_PostNameUtils::getNameUc();
        switch ($component->name) {
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



