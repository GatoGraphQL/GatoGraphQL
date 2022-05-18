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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_USERAVATAR_UPDATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return \PoP\Root\App::getState('current-user-id');
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    protected function getFeedbackmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages::MODULE_FEEDBACKMESSAGE_USERAVATAR_UPDATE];
        }

        return parent::getFeedbackmessageModule($component);
    }

    protected function getCheckpointmessageModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointmessageModule($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $ret[] = [PoP_UserAvatarProcessors_Module_Processor_UserForms::class, PoP_UserAvatarProcessors_Module_Processor_UserForms::MODULE_FORM_USERAVATAR_UPDATE];
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;
        }

        return $ret;
    }

    public function getDataloadSource(array $component, array &$props): string
    {
        // Replace the routes in the "componentPaths" and "actionpath" parameters:
        // point to the "execute" block instead
        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                // We also must add ?action=execute to tell the componentroutingprocessor to fetch the module with the "execute" componentAtts
                return GeneralUtils::addQueryArgs([
                    \PoP\ComponentModel\Constants\Params::ACTIONS.'[]' => POP_ACTION_USERAVATAR_EXECUTEUPDATE,
                ], parent::getDataloadSource($component, $props));
        }

        return parent::getDataloadSource($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                // Two different actions, handled through $componentAtts:
                // 1. Upload the image to the S3 repository, when first accessing the page
                // 2. Update the avatar, on the POST operation
                $componentAtts = (count($component) >= 3) ? $component[2] : null;
                if ($componentAtts && $componentAtts['executeupdate']) {
                    return $this->instanceManager->getInstance(UpdateUserAvatarMutationResolverBridge::class);
                }

                return $this->instanceManager->getInstance(FileUploadPictureMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function shouldExecuteMutation(array $component, array &$props): bool
    {
        // Always execute this action
        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $componentAtts = (count($component) >= 3) ? $component[2] : null;
                if (!$componentAtts || !$componentAtts['executeupdate']) {
                    return true;
                }
                break;
        }

        return parent::shouldExecuteMutation($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_USERAVATAR_UPDATE:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('update your picture', 'pop-useravatar-processors'));
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Saving...', 'pop-useravatar-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



