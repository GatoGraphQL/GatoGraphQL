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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_USERAVATAR_UPDATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return \PoP\Root\App::getState('current-user-id');
        }

        return parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    protected function getFeedbackmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_USERAVATAR_UPDATE];
        }

        return parent::getFeedbackmessageModule($componentVariation);
    }

    protected function getCheckpointmessageModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($componentVariation);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $ret[] = [PoP_UserAvatarProcessors_Module_Processor_UserForms::class, PoP_UserAvatarProcessors_Module_Processor_UserForms::MODULE_FORM_USERAVATAR_UPDATE];
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;
        }

        return $ret;
    }

    public function getDataloadSource(array $componentVariation, array &$props): string
    {
        // Replace the routes in the "modulepaths" and "actionpath" parameters:
        // point to the "execute" block instead
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                // We also must add ?action=execute to tell the componentroutingprocessor to fetch the module with the "execute" moduleAtts
                return GeneralUtils::addQueryArgs([
                    \PoP\ComponentModel\Constants\Params::ACTIONS.'[]' => POP_ACTION_USERAVATAR_EXECUTEUPDATE,
                ], parent::getDataloadSource($componentVariation, $props));
        }

        return parent::getDataloadSource($componentVariation, $props);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                // Two different actions, handled through $moduleAtts:
                // 1. Upload the image to the S3 repository, when first accessing the page
                // 2. Update the avatar, on the POST operation
                $moduleAtts = (count($componentVariation) >= 3) ? $componentVariation[2] : null;
                if ($moduleAtts && $moduleAtts['executeupdate']) {
                    return $this->instanceManager->getInstance(UpdateUserAvatarMutationResolverBridge::class);
                }

                return $this->instanceManager->getInstance(FileUploadPictureMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }

    public function shouldExecuteMutation(array $componentVariation, array &$props): bool
    {
        // Always execute this action
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $moduleAtts = (count($componentVariation) >= 3) ? $componentVariation[2] : null;
                if (!$moduleAtts || !$moduleAtts['executeupdate']) {
                    return true;
                }
                break;
        }

        return parent::shouldExecuteMutation($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('update your picture', 'pop-useravatar-processors'));
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Saving...', 'pop-useravatar-processors'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



