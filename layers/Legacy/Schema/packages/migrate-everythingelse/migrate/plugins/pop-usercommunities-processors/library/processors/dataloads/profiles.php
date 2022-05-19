<?php
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\ComponentProcessors\ObjectIDFromURLParamComponentProcessorTrait;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\EditMembershipMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\InviteMembersMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\UpdateMyCommunitiesMutationResolverBridge;

class GD_URE_Module_Processor_ProfileDataloads extends PoP_Module_Processor_DataloadsBase
{
    use ObjectIDFromURLParamComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE = 'dataload-mycommunities-update';
    public final const COMPONENT_DATALOAD_INVITENEWMEMBERS = 'dataload-invitemembers';
    public final const COMPONENT_DATALOAD_EDITMEMBERSHIP = 'dataload-editmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE],
            [self::class, self::COMPONENT_DATALOAD_INVITENEWMEMBERS],
            [self::class, self::COMPONENT_DATALOAD_EDITMEMBERSHIP],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_EDITMEMBERSHIP => POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP,
            self::COMPONENT_DATALOAD_INVITENEWMEMBERS => POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS,
            self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE => POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_INVITENEWMEMBERS:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_INVITENEWMEMBERS:
                return $this->instanceManager->getInstance(InviteMembersMutationResolverBridge::class);

            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                return $this->instanceManager->getInstance(EditMembershipMutationResolverBridge::class);

            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
                return $this->instanceManager->getInstance(UpdateMyCommunitiesMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
                $ret[] = [GD_URE_Module_Processor_ProfileForms::class, GD_URE_Module_Processor_ProfileForms::COMPONENT_FORM_MYCOMMUNITIES_UPDATE];
                break;

            case self::COMPONENT_DATALOAD_INVITENEWMEMBERS:
                $ret[] = [PoP_Module_Processor_UserForms::class, PoP_Module_Processor_UserForms::COMPONENT_FORM_INVITENEWUSERS];
                break;

            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                $ret[] = [GD_URE_Module_Processor_CustomContents::class, GD_URE_Module_Processor_CustomContents::COMPONENT_URE_CONTENT_MEMBER];
                $ret[] = [GD_URE_Module_Processor_ProfileForms::class, GD_URE_Module_Processor_ProfileForms::COMPONENT_FORM_EDITMEMBERSHIP];
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;

         // case self::COMPONENT_DATALOAD_INVITENEWMEMBERS:

         //     $this->addJsmethod($ret, 'destroyPageOnUserNoRole');
         //     break;

         // case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:

         //     // $this->addJsmethod($ret, 'destroyPageOnUserNoRole');
         //     break;
        }

        return $ret;
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
                return [GD_URE_Module_Processor_ProfileFeedbackMessages::class, GD_URE_Module_Processor_ProfileFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES];

            case self::COMPONENT_DATALOAD_INVITENEWMEMBERS:
                return [GD_URE_Module_Processor_ProfileFeedbackMessages::class, GD_URE_Module_Processor_ProfileFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_INVITENEWMEMBERS];

            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                return [GD_URE_Module_Processor_ProfileFeedbackMessages::class, GD_URE_Module_Processor_ProfileFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_EDITMEMBERSHIP];
        }

        return parent::getFeedbackmessageModule($component);
    }

    protected function getCheckpointmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_LOGGEDIN];

            case self::COMPONENT_DATALOAD_INVITENEWMEMBERS:
                return [GD_UserCommunities_Module_Processor_UserCheckpointMessages::class, GD_UserCommunities_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_PROFILECOMMUNITY];

            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                return [GD_UserCommunities_Module_Processor_UserCheckpointMessages::class, GD_UserCommunities_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP];
        }

        return parent::getCheckpointmessageModule($component);
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                return $this->getObjectIDFromURLParam($component, $props, $data_properties);
            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
                return \PoP\Root\App::getState('current-user-id');
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    protected function getObjectIDParamName(array $component, array &$props, array &$data_properties): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                return \PoPCMSSchema\Users\Constants\InputNames::USER_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_EditMembership::class);
        }

        return parent::getQueryInputOutputHandler($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'ure-popprocessors'));
                break;

            case self::COMPONENT_DATALOAD_INVITENEWMEMBERS:
            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                // $this->mergeProp($component, $props, 'params', array(
                //     'data-neededrole' => GD_URE_ROLE_COMMUNITY
                // ));
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'ure-popprocessors'));
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYCOMMUNITIES_UPDATE:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('update your communities', 'poptheme-wassup'));
                break;

            case self::COMPONENT_DATALOAD_INVITENEWMEMBERS:
                $this->setProp([GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITY], $props, 'action', TranslationAPIFacade::getInstance()->__('invite members', 'poptheme-wassup'));
                break;

            case self::COMPONENT_DATALOAD_EDITMEMBERSHIP:
                $this->setProp([GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP], $props, 'action', TranslationAPIFacade::getInstance()->__('edit users\' membership', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($component, $props);
    }
}



