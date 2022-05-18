<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\LocationPostMutations\MutationResolverBridges\CreateLocationPostMutationResolverBridge;
use PoPSitesWassup\LocationPostMutations\MutationResolverBridges\UpdateLocationPostMutationResolverBridge;

class GD_Custom_EM_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const COMPONENT_DATALOAD_LOCATIONPOST_UPDATE = 'dataload-locationpost-update';
    public final const COMPONENT_DATALOAD_LOCATIONPOST_CREATE = 'dataload-locationpost-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOST_UPDATE],
            [self::class, self::COMPONENT_DATALOAD_LOCATIONPOST_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_LOCATIONPOST_CREATE => POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
            self::COMPONENT_DATALOAD_LOCATIONPOST_UPDATE => POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LOCATIONPOST_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::COMPONENT_DATALOAD_LOCATIONPOST_UPDATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_LOCATIONPOST],
            self::COMPONENT_DATALOAD_LOCATIONPOST_CREATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_LOCATIONPOST],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LOCATIONPOST_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LOCATIONPOST_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LOCATIONPOST_UPDATE:
            case self::COMPONENT_DATALOAD_LOCATIONPOST_CREATE:
                $name = PoP_LocationPosts_PostNameUtils::getNameUc();
                if ($this->isUpdate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-locationpostscreation-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_LOCATIONPOST_CREATE:
                return $this->instanceManager->getInstance(CreateLocationPostMutationResolverBridge::class);
            case self::COMPONENT_DATALOAD_LOCATIONPOST_UPDATE:
                return $this->instanceManager->getInstance(UpdateLocationPostMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}


