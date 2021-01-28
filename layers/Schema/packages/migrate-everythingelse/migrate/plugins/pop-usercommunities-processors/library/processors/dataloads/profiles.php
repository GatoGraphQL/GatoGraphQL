<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\Engine\ModuleProcessors\DBObjectIDFromURLParamModuleProcessorTrait;
use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\EditMembershipMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\UpdateMyCommunitiesMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\InviteMembersMutationResolverBridge;

class GD_URE_Module_Processor_ProfileDataloads extends PoP_Module_Processor_DataloadsBase
{
    use DBObjectIDFromURLParamModuleProcessorTrait;

    public const MODULE_DATALOAD_MYCOMMUNITIES_UPDATE = 'dataload-mycommunities-update';
    public const MODULE_DATALOAD_INVITENEWMEMBERS = 'dataload-invitemembers';
    public const MODULE_DATALOAD_EDITMEMBERSHIP = 'dataload-editmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE],
            [self::class, self::MODULE_DATALOAD_INVITENEWMEMBERS],
            [self::class, self::MODULE_DATALOAD_EDITMEMBERSHIP],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_EDITMEMBERSHIP => POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP,
            self::MODULE_DATALOAD_INVITENEWMEMBERS => POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS,
            self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE => POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_INVITENEWMEMBERS:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_INVITENEWMEMBERS:
                return InviteMembersMutationResolverBridge::class;

            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                return EditMembershipMutationResolverBridge::class;

            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
                return UpdateMyCommunitiesMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
                $ret[] = [GD_URE_Module_Processor_ProfileForms::class, GD_URE_Module_Processor_ProfileForms::MODULE_FORM_MYCOMMUNITIES_UPDATE];
                break;

            case self::MODULE_DATALOAD_INVITENEWMEMBERS:
                $ret[] = [PoP_Module_Processor_UserForms::class, PoP_Module_Processor_UserForms::MODULE_FORM_INVITENEWUSERS];
                break;

            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                $ret[] = [GD_URE_Module_Processor_CustomContents::class, GD_URE_Module_Processor_CustomContents::MODULE_URE_CONTENT_MEMBER];
                $ret[] = [GD_URE_Module_Processor_ProfileForms::class, GD_URE_Module_Processor_ProfileForms::MODULE_FORM_EDITMEMBERSHIP];
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;

         // case self::MODULE_DATALOAD_INVITENEWMEMBERS:

         //     $this->addJsmethod($ret, 'destroyPageOnUserNoRole');
         //     break;

         // case self::MODULE_DATALOAD_EDITMEMBERSHIP:

         //     // $this->addJsmethod($ret, 'destroyPageOnUserNoRole');
         //     break;
        }

        return $ret;
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
                return [GD_URE_Module_Processor_ProfileFeedbackMessages::class, GD_URE_Module_Processor_ProfileFeedbackMessages::MODULE_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES];

            case self::MODULE_DATALOAD_INVITENEWMEMBERS:
                return [GD_URE_Module_Processor_ProfileFeedbackMessages::class, GD_URE_Module_Processor_ProfileFeedbackMessages::MODULE_FEEDBACKMESSAGE_INVITENEWMEMBERS];

            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                return [GD_URE_Module_Processor_ProfileFeedbackMessages::class, GD_URE_Module_Processor_ProfileFeedbackMessages::MODULE_FEEDBACKMESSAGE_EDITMEMBERSHIP];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getCheckpointmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];

            case self::MODULE_DATALOAD_INVITENEWMEMBERS:
                return [GD_UserCommunities_Module_Processor_UserCheckpointMessages::class, GD_UserCommunities_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITY];

            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                return [GD_UserCommunities_Module_Processor_UserCheckpointMessages::class, GD_UserCommunities_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP];
        }

        return parent::getCheckpointmessageModule($module);
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                return $this->getDBObjectIDFromURLParam($module, $props, $data_properties);
            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
                $vars = ApplicationState::getVars();
                return $vars['global-userstate']['current-user-id'];
        }
        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    protected function getDBObjectIDParamName(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                return POP_INPUTNAME_USERID;
        }
        return null;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                return GD_DataLoad_QueryInputOutputHandler_EditMembership::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'ure-popprocessors'));
                break;

            case self::MODULE_DATALOAD_INVITENEWMEMBERS:
            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                // $this->mergeProp($module, $props, 'params', array(
                //     'data-neededrole' => GD_URE_ROLE_COMMUNITY
                // ));
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'ure-popprocessors'));
                break;
        }

        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('update your communities', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_INVITENEWMEMBERS:
                $this->setProp([GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITY], $props, 'action', TranslationAPIFacade::getInstance()->__('invite members', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_EDITMEMBERSHIP:
                $this->setProp([GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP], $props, 'action', TranslationAPIFacade::getInstance()->__('edit users\' membership', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($module, $props);
    }
}



