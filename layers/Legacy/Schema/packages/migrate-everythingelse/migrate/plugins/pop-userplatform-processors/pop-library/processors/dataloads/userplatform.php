<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\ChangeUserPasswordMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\InviteUsersMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\UpdateMyPreferencesMutationResolverBridge;

class PoP_UserPlatform_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_USER_CHANGEPASSWORD = 'dataload-user-changepwd';
    public final const MODULE_DATALOAD_MYPREFERENCES = 'dataload-mypreferences';
    public final const MODULE_DATALOAD_INVITENEWUSERS = 'dataload-inviteusers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_USER_CHANGEPASSWORD],
            [self::class, self::MODULE_DATALOAD_MYPREFERENCES],
            [self::class, self::MODULE_DATALOAD_INVITENEWUSERS],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_INVITENEWUSERS => POP_USERPLATFORM_ROUTE_INVITENEWUSERS,
            self::MODULE_DATALOAD_MYPREFERENCES => POP_USERPLATFORM_ROUTE_MYPREFERENCES,
            self::MODULE_DATALOAD_USER_CHANGEPASSWORD => POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_INVITENEWUSERS:
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($componentVariation, $props);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                return $this->instanceManager->getInstance(ChangeUserPasswordMutationResolverBridge::class);

            case self::MODULE_DATALOAD_MYPREFERENCES:
                return $this->instanceManager->getInstance(UpdateMyPreferencesMutationResolverBridge::class);

            case self::MODULE_DATALOAD_INVITENEWUSERS:
                return $this->instanceManager->getInstance(InviteUsersMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                break;

            case self::MODULE_DATALOAD_MYPREFERENCES:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_MYPREFERENCES:
                return \PoP\Root\App::getState('current-user-id');
        }
        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_MYPREFERENCES:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inner_modules = array(
            self::MODULE_DATALOAD_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_USER_CHANGEPASSWORD],
            self::MODULE_DATALOAD_MYPREFERENCES => [PoP_Module_Processor_UserForms::class, PoP_Module_Processor_UserForms::MODULE_FORM_MYPREFERENCES],
            self::MODULE_DATALOAD_INVITENEWUSERS => [PoP_Module_Processor_UserForms::class, PoP_Module_Processor_UserForms::MODULE_FORM_INVITENEWUSERS],
        );

        if ($inner = $inner_modules[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getFeedbackmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_USER_CHANGEPASSWORD];

            case self::MODULE_DATALOAD_MYPREFERENCES:
                return [PoP_Module_Processor_UserFeedbackMessages::class, PoP_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_MYPREFERENCES];

            case self::MODULE_DATALOAD_INVITENEWUSERS:
                return [PoP_Core_Module_Processor_FeedbackMessages::class, PoP_Core_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_INVITENEWUSERS];
        }

        return parent::getFeedbackmessageModule($componentVariation);
    }

    protected function getCheckpointmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];

            case self::MODULE_DATALOAD_MYPREFERENCES:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                if ($extra_checkpoint_msgs = \PoP\Root\App::applyFilters(
                    'PoP_UserLogin_Module_Processor_Blocks:extra-checkpoint-msgs:change-pwd',
                    array(),
                    $componentVariation
                )
                ) {
                    $this->mergeProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'extra-checkpoint-messages', $extra_checkpoint_msgs);
                }

                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('change your password', 'poptheme-wassup'));
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-coreprocessors'));
                break;

            case self::MODULE_DATALOAD_MYPREFERENCES:
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Saving...', 'pop-coreprocessors'));
                break;

            case self::MODULE_DATALOAD_INVITENEWUSERS:
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



