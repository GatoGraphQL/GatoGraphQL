<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\FileUploadPictureMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\UpdateUserAvatarMutationResolverBridge;

class PoP_UserAvatarProcessors_Module_Processor_UserDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_USERAVATAR_UPDATE = 'dataload-useravatar-update';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_USERAVATAR_UPDATE,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_USERAVATAR_UPDATE => POP_USERAVATAR_ROUTE_EDITAVATAR,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getObjectIDOrIDs(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                return \PoP\Root\App::getState('current-user-id');
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    protected function getFeedbackMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                return [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_USERAVATAR_UPDATE];
        }

        return parent::getFeedbackMessageComponent($component);
    }

    protected function getCheckpointMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_LOGGEDIN];
        }

        return parent::getCheckpointMessageComponent($component);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                $ret[] = [PoP_UserAvatarProcessors_Module_Processor_UserForms::class, PoP_UserAvatarProcessors_Module_Processor_UserForms::COMPONENT_FORM_USERAVATAR_UPDATE];
                break;
        }

        return $ret;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;
        }

        return $ret;
    }

    public function getDataloadSource(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        // Replace the routes in the "componentPaths" and "actionpath" parameters:
        // point to the "execute" block instead
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                // We also must add ?action=execute to tell the componentroutingprocessor to fetch the module with the "execute" componentAtts
                return GeneralUtils::addQueryArgs([
                    \PoP\ComponentModel\Constants\Params::ACTIONS.'[]' => POP_ACTION_USERAVATAR_EXECUTEUPDATE,
                ], parent::getDataloadSource($component, $props));
        }

        return parent::getDataloadSource($component, $props);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                // Two different actions, handled through $componentAtts:
                // 1. Upload the image to the S3 repository, when first accessing the page
                // 2. Update the avatar, on the POST operation
                if ($component->atts['executeupdate'] ?? null) {
                    return $this->instanceManager->getInstance(UpdateUserAvatarMutationResolverBridge::class);
                }

                return $this->instanceManager->getInstance(FileUploadPictureMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function shouldExecuteMutation(\PoP\ComponentModel\Component\Component $component, array &$props): bool
    {
        // Always execute this action
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                if (!($component->atts['executeupdate'] ?? null)) {
                    return true;
                }
                break;
        }

        return parent::shouldExecuteMutation($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_USERAVATAR_UPDATE:
                $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('update your picture', 'pop-useravatar-processors'));
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Saving...', 'pop-useravatar-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



