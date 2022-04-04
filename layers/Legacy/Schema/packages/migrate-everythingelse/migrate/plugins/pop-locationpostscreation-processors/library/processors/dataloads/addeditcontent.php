<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\LocationPostMutations\MutationResolverBridges\CreateLocationPostMutationResolverBridge;
use PoPSitesWassup\LocationPostMutations\MutationResolverBridges\UpdateLocationPostMutationResolverBridge;

class GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const MODULE_DATALOAD_LOCATIONPOST_UPDATE = 'dataload-locationpost-update';
    public final const MODULE_DATALOAD_LOCATIONPOST_CREATE = 'dataload-locationpost-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOST_UPDATE],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOST_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_LOCATIONPOST_CREATE => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
            self::MODULE_DATALOAD_LOCATIONPOST_UPDATE => POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOST_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_DATALOAD_LOCATIONPOST_UPDATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::MODULE_FORM_LOCATIONPOST],
            self::MODULE_DATALOAD_LOCATIONPOST_CREATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::MODULE_FORM_LOCATIONPOST],
        );
        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOST_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOST_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOST_UPDATE:
            case self::MODULE_DATALOAD_LOCATIONPOST_CREATE:
                $name = PoP_LocationPosts_PostNameUtils::getNameUc();
                if ($this->isUpdate($module)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($module)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-locationpostscreation-processors'));
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOST_CREATE:
                return $this->instanceManager->getInstance(CreateLocationPostMutationResolverBridge::class);
            case self::MODULE_DATALOAD_LOCATIONPOST_UPDATE:
                return $this->instanceManager->getInstance(UpdateLocationPostMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }
}


