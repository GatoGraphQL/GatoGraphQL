<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\ChangeUserPasswordMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\InviteUsersMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\UpdateMyPreferencesMutationResolverBridge;

class PoP_UserPlatform_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_USER_CHANGEPASSWORD = 'dataload-user-changepwd';
    public final const COMPONENT_DATALOAD_MYPREFERENCES = 'dataload-mypreferences';
    public final const COMPONENT_DATALOAD_INVITENEWUSERS = 'dataload-inviteusers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD],
            [self::class, self::COMPONENT_DATALOAD_MYPREFERENCES],
            [self::class, self::COMPONENT_DATALOAD_INVITENEWUSERS],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_INVITENEWUSERS => POP_USERPLATFORM_ROUTE_INVITENEWUSERS,
            self::COMPONENT_DATALOAD_MYPREFERENCES => POP_USERPLATFORM_ROUTE_MYPREFERENCES,
            self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD => POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_INVITENEWUSERS:
            case self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD:
                return $this->instanceManager->getInstance(ChangeUserPasswordMutationResolverBridge::class);

            case self::COMPONENT_DATALOAD_MYPREFERENCES:
                return $this->instanceManager->getInstance(UpdateMyPreferencesMutationResolverBridge::class);

            case self::COMPONENT_DATALOAD_INVITENEWUSERS:
                return $this->instanceManager->getInstance(InviteUsersMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                break;

            case self::COMPONENT_DATALOAD_MYPREFERENCES:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYPREFERENCES:
                return \PoP\Root\App::getState('current-user-id');
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_MYPREFERENCES:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inner_components = array(
            self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::COMPONENT_FORM_USER_CHANGEPASSWORD],
            self::COMPONENT_DATALOAD_MYPREFERENCES => [PoP_Module_Processor_UserForms::class, PoP_Module_Processor_UserForms::COMPONENT_FORM_MYPREFERENCES],
            self::COMPONENT_DATALOAD_INVITENEWUSERS => [PoP_Module_Processor_UserForms::class, PoP_Module_Processor_UserForms::COMPONENT_FORM_INVITENEWUSERS],
        );

        if ($inner = $inner_components[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_USER_CHANGEPASSWORD];

            case self::COMPONENT_DATALOAD_MYPREFERENCES:
                return [PoP_Module_Processor_UserFeedbackMessages::class, PoP_Module_Processor_UserFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_MYPREFERENCES];

            case self::COMPONENT_DATALOAD_INVITENEWUSERS:
                return [PoP_Core_Module_Processor_FeedbackMessages::class, PoP_Core_Module_Processor_FeedbackMessages::COMPONENT_FEEDBACKMESSAGE_INVITENEWUSERS];
        }

        return parent::getFeedbackmessageModule($component);
    }

    protected function getCheckpointmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_LOGGEDIN];

            case self::COMPONENT_DATALOAD_MYPREFERENCES:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_USER_CHANGEPASSWORD:
                if ($extra_checkpoint_msgs = \PoP\Root\App::applyFilters(
                    'PoP_UserLogin_Module_Processor_Blocks:extra-checkpoint-msgs:change-pwd',
                    array(),
                    $component
                )
                ) {
                    $this->mergeProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'extra-checkpoint-messages', $extra_checkpoint_msgs);
                }

                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('change your password', 'poptheme-wassup'));
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-coreprocessors'));
                break;

            case self::COMPONENT_DATALOAD_MYPREFERENCES:
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Saving...', 'pop-coreprocessors'));
                break;

            case self::COMPONENT_DATALOAD_INVITENEWUSERS:
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



