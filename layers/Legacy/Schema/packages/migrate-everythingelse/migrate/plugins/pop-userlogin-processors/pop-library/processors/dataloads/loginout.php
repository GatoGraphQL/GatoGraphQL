<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\UserStateMutations\MutationResolverBridges\LoginMutationResolverBridge;
use PoPSitesWassup\UserStateMutations\MutationResolverBridges\LogoutMutationResolverBridge;
use PoPSitesWassup\UserStateMutations\MutationResolverBridges\LostPasswordMutationResolverBridge;
use PoPSitesWassup\UserStateMutations\MutationResolverBridges\ResetLostPasswordMutationResolverBridge;

class PoP_UserLogin_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_LOGIN = 'dataload-login';
    public final const MODULE_DATALOAD_LOSTPWD = 'dataload-lostpwd';
    public final const MODULE_DATALOAD_LOSTPWDRESET = 'dataload-lostpwdreset';
    public final const MODULE_DATALOAD_LOGOUT = 'dataload-logout';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LOGIN],
            [self::class, self::MODULE_DATALOAD_LOSTPWD],
            [self::class, self::MODULE_DATALOAD_LOSTPWDRESET],
            [self::class, self::MODULE_DATALOAD_LOGOUT],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_LOGOUT => POP_USERLOGIN_ROUTE_LOGOUT,
            self::MODULE_DATALOAD_LOSTPWD => POP_USERLOGIN_ROUTE_LOSTPWD,
            self::MODULE_DATALOAD_LOSTPWDRESET => POP_USERLOGIN_ROUTE_LOSTPWDRESET,
            self::MODULE_DATALOAD_LOGIN => POP_USERLOGIN_ROUTE_LOGIN,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOGOUT:
            case self::MODULE_DATALOAD_LOSTPWD:
            case self::MODULE_DATALOAD_LOSTPWDRESET:
            case self::MODULE_DATALOAD_LOGIN:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($componentVariation, $props);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inner_componentVariations = array(
            self::MODULE_DATALOAD_LOGIN => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_LOGIN],
            self::MODULE_DATALOAD_LOSTPWD => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_LOSTPWD],
            self::MODULE_DATALOAD_LOSTPWDRESET => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_LOSTPWDRESET],
            self::MODULE_DATALOAD_LOGOUT => [GD_UserLogin_Module_Processor_UserForms::class, GD_UserLogin_Module_Processor_UserForms::MODULE_FORM_LOGOUT],
        );

        if ($inner = $inner_componentVariations[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOGIN:
                return $this->instanceManager->getInstance(LoginMutationResolverBridge::class);

            case self::MODULE_DATALOAD_LOSTPWD:
                return $this->instanceManager->getInstance(LostPasswordMutationResolverBridge::class);

            case self::MODULE_DATALOAD_LOSTPWDRESET:
                return $this->instanceManager->getInstance(ResetLostPasswordMutationResolverBridge::class);

            case self::MODULE_DATALOAD_LOGOUT:
                return $this->instanceManager->getInstance(LogoutMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }

    protected function getFeedbackmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOGIN:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_LOGIN];

            case self::MODULE_DATALOAD_LOSTPWD:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_LOSTPWD];

            case self::MODULE_DATALOAD_LOSTPWDRESET:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_LOSTPWDRESET];

            case self::MODULE_DATALOAD_LOGOUT:
                return [GD_UserLogin_Module_Processor_UserFeedbackMessages::class, GD_UserLogin_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_LOGOUT];
        }

        return parent::getFeedbackmessageModule($componentVariation);
    }

    protected function getCheckpointmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOSTPWD:
            case self::MODULE_DATALOAD_LOSTPWDRESET:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_NOTLOGGEDIN];
        }

        return parent::getCheckpointmessageModule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LOGIN:
            case self::MODULE_DATALOAD_LOSTPWD:
            case self::MODULE_DATALOAD_LOSTPWDRESET:
            case self::MODULE_DATALOAD_LOGOUT:
                // Change the 'Loading' message in the Status
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Sending...', 'pop-coreprocessors'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



