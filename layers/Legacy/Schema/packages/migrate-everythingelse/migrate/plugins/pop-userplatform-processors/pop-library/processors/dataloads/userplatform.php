<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\ChangeUserPasswordMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\InviteUsersMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\UpdateMyPreferencesMutationResolverBridge;

class PoP_UserPlatform_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public const MODULE_DATALOAD_USER_CHANGEPASSWORD = 'dataload-user-changepwd';
    public const MODULE_DATALOAD_MYPREFERENCES = 'dataload-mypreferences';
    public const MODULE_DATALOAD_INVITENEWUSERS = 'dataload-inviteusers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_USER_CHANGEPASSWORD],
            [self::class, self::MODULE_DATALOAD_MYPREFERENCES],
            [self::class, self::MODULE_DATALOAD_INVITENEWUSERS],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_INVITENEWUSERS => POP_USERPLATFORM_ROUTE_INVITENEWUSERS,
            self::MODULE_DATALOAD_MYPREFERENCES => POP_USERPLATFORM_ROUTE_MYPREFERENCES,
            self::MODULE_DATALOAD_USER_CHANGEPASSWORD => POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_INVITENEWUSERS:
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                return $this->instanceManager->getInstance(ChangeUserPasswordMutationResolverBridge::class);

            case self::MODULE_DATALOAD_MYPREFERENCES:
                return $this->instanceManager->getInstance(UpdateMyPreferencesMutationResolverBridge::class);

            case self::MODULE_DATALOAD_INVITENEWUSERS:
                return $this->instanceManager->getInstance(InviteUsersMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
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

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYPREFERENCES:
                return \PoP\Root\App::getState('current-user-id');
        }
        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYPREFERENCES:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inner_modules = array(
            self::MODULE_DATALOAD_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_USER_CHANGEPASSWORD],
            self::MODULE_DATALOAD_MYPREFERENCES => [PoP_Module_Processor_UserForms::class, PoP_Module_Processor_UserForms::MODULE_FORM_MYPREFERENCES],
            self::MODULE_DATALOAD_INVITENEWUSERS => [PoP_Module_Processor_UserForms::class, PoP_Module_Processor_UserForms::MODULE_FORM_INVITENEWUSERS],
        );

        if ($inner = $inner_modules[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_USER_CHANGEPASSWORD];

            case self::MODULE_DATALOAD_MYPREFERENCES:
                return [PoP_Module_Processor_UserFeedbackMessages::class, PoP_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_MYPREFERENCES];

            case self::MODULE_DATALOAD_INVITENEWUSERS:
                return [PoP_Core_Module_Processor_FeedbackMessages::class, PoP_Core_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_INVITENEWUSERS];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getCheckpointmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];

            case self::MODULE_DATALOAD_MYPREFERENCES:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USER_CHANGEPASSWORD:
                if ($extra_checkpoint_msgs = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_UserLogin_Module_Processor_Blocks:extra-checkpoint-msgs:change-pwd',
                    array(),
                    $module
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

        parent::initModelProps($module, $props);
    }
}



