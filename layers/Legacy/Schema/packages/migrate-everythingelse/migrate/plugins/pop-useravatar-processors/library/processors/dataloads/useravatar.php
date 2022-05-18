<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\FileUploadPictureMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\UpdateUserAvatarMutationResolverBridge;

class PoP_UserAvatarProcessors_Module_Processor_UserDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_USERAVATAR_UPDATE = 'dataload-useravatar-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_USERAVATAR_UPDATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return \PoP\Root\App::getState('current-user-id');
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
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
                // We also must add ?action=execute to tell the componentroutingprocessor to fetch the module with the "execute" moduleAtts
                return GeneralUtils::addQueryArgs([
                    \PoP\ComponentModel\Constants\Params::ACTIONS.'[]' => POP_ACTION_USERAVATAR_EXECUTEUPDATE,
                ], parent::getDataloadSource($module, $props));
        }

        return parent::getDataloadSource($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                // Two different actions, handled through $moduleAtts:
                // 1. Upload the image to the S3 repository, when first accessing the page
                // 2. Update the avatar, on the POST operation
                $moduleAtts = (count($module) >= 3) ? $module[2] : null;
                if ($moduleAtts && $moduleAtts['executeupdate']) {
                    return $this->instanceManager->getInstance(UpdateUserAvatarMutationResolverBridge::class);
                }

                return $this->instanceManager->getInstance(FileUploadPictureMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
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

    public function initModelProps(array $module, array &$props): void
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



