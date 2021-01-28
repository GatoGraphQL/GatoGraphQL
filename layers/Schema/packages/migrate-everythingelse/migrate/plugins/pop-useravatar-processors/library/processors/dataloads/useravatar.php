<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\UpdateUserAvatarMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\MutationResolverBridges\FileUploadPictureMutationResolverBridge;

class PoP_UserAvatarProcessors_Module_Processor_UserDataloads extends PoP_Module_Processor_DataloadsBase
{
    public const MODULE_DATALOAD_USERAVATAR_UPDATE = 'dataload-useravatar-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_USERAVATAR_UPDATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        $vars = ApplicationState::getVars();
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return $vars['global-userstate']['current-user-id'];
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_USERAVATAR_UPDATE];
        }

        return parent::getFeedbackmessageModule($module);
    }

    protected function getCheckpointmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $ret[] = [PoP_UserAvatarProcessors_Module_Processor_UserForms::class, PoP_UserAvatarProcessors_Module_Processor_UserForms::MODULE_FORM_USERAVATAR_UPDATE];
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;
        }

        return $ret;
    }

    public function getDataloadSource(array $module, array &$props): string
    {
        // Replace the routes in the "modulepaths" and "actionpath" parameters:
        // point to the "execute" block instead
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                // We also must add ?action=execute to tell the routemoduleprocessor to fetch the module with the "execute" moduleAtts
                return GeneralUtils::addQueryArgs([
                    \PoP\ComponentModel\Constants\Params::ACTIONS.'[]' => POP_ACTION_USERAVATAR_EXECUTEUPDATE,
                ], parent::getDataloadSource($module, $props));
        }

        return parent::getDataloadSource($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                // Two different actions, handled through $moduleAtts:
                // 1. Upload the image to the S3 repository, when first accessing the page
                // 2. Update the avatar, on the POST operation
                $moduleAtts = (count($module) >= 3) ? $module[2] : null;
                if ($moduleAtts && $moduleAtts['executeupdate']) {
                    return UpdateUserAvatarMutationResolverBridge::class;
                }

                return FileUploadPictureMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    public function shouldExecuteMutation(array $module, array &$props): bool
    {
        // Always execute this action
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $moduleAtts = (count($module) >= 3) ? $module[2] : null;
                if (!$moduleAtts || !$moduleAtts['executeupdate']) {
                    return true;
                }
                break;
        }

        return parent::shouldExecuteMutation($module, $props);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('update your picture', 'pop-useravatar-processors'));
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Saving...', 'pop-useravatar-processors'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



