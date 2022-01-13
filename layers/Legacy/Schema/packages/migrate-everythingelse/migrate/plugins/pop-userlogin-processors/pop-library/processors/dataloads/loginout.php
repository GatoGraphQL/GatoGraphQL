<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\UserStateMutations\MutationResolverBridges\LoginMutationResolverBridge;
use PoPSitesWassup\UserStateMutations\MutationResolverBridges\LogoutMutationResolverBridge;
use PoPSitesWassup\UserStateMutations\MutationResolverBridges\LostPasswordMutationResolverBridge;
use PoPSitesWassup\UserStateMutations\MutationResolverBridges\ResetLostPasswordMutationResolverBridge;

class PoP_UserLogin_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public const MODULE_DATALOAD_LOGIN = 'dataload-login';
    public const MODULE_DATALOAD_LOSTPWD = 'dataload-lostpwd';
    public const MODULE_DATALOAD_LOSTPWDRESET = 'dataload-lostpwdreset';
    public const MODULE_DATALOAD_LOGOUT = 'dataload-logout';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LOGIN],
            [self::class, self::MODULE_DATALOAD_LOSTPWD],
            [self::class, self::MODULE_DATALOAD_LOSTPWDRESET],
            [self::class, self::MODULE_DATALOAD_LOGOUT],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_LOGOUT => POP_USERLOGIN_ROUTE_LOGOUT,
            self::MODULE_DATALOAD_LOSTPWD => POP_USERLOGIN_ROUTE_LOSTPWD,
            self::MODULE_DATALOAD_LOSTPWDRESET => POP_USERLOGIN_ROUTE_LOSTPWDRESET,
            self::MODULE_DATALOAD_LOGIN => POP_USERLOGIN_ROUTE_LOGIN,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOGOUT:
            case self::MODULE_DATALOAD_LOSTPWD:
            case self::MODULE_DATALOAD_LOSTPWDRESET:
            case self::MODULE_DATALOAD_LOGIN:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inner_modules = array(
            self::MODULE_DATALOAD_LOGIN => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_LOGIN],
            self::MODULE_DATALOAD_LOSTPWD => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_LOSTPWD],
            self::MODULE_DATALOAD_LOSTPWDRESET => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_LOSTPWDRESET],
            self::MODULE_DATALOAD_LOGOUT => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_LOGOUT],
        );

        if ($inner = $inner_modules[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOGIN:
                return $this->instanceManager->getInstance(LoginMutationResolverBridge::class);

            case self::MODULE_DATALOAD_LOSTPWD:
                return $this->instanceManager->getInstance(LostPasswordMutationResolverBridge::class);

            case self::MODULE_DATALOAD_LOSTPWDRESET:
                return $this->instanceManager->getInstance(ResetLostPasswordMutationResolverBridge::class);

            case self::MODULE_DATALOAD_LOGOUT:
                return $this->instanceManager->getInstance(LogoutMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOGIN:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_LOGIN];

            case self::MODULE_DATALOAD_LOSTPWD:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_LOSTPWD];

            case self::MODULE_DATALOAD_LOSTPWDRESET:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_LOSTPWDRESET];

            case self::MODULE_DATALOAD_LOGOUT:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_LOGOUT];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getCheckpointmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOSTPWD:
            case self::MODULE_DATALOAD_LOSTPWDRESET:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_NOTLOGGEDIN];
        }

        return parent::getCheckpointmessageModule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOGIN:
            case self::MODULE_DATALOAD_LOSTPWD:
            case self::MODULE_DATALOAD_LOSTPWDRESET:
            case self::MODULE_DATALOAD_LOGOUT:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



