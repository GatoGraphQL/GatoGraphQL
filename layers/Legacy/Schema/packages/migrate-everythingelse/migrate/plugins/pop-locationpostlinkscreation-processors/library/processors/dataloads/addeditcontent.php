<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\LocationPostLinkMutations\MutationResolvers\CreateLocationPostLinkMutationResolver;
use PoPSitesWassup\LocationPostLinkMutations\MutationResolvers\UpdateLocationPostLinkMutationResolver;

class PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const COMPONENT_DATALOAD_LOCATIONPOSTLINK_UPDATE = 'dataload-locationpostlink-update';
    public final const COMPONENT_DATALOAD_LOCATIONPOSTLINK_CREATE = 'dataload-locationpostlink-create';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_UPDATE,
            self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_CREATE,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_CREATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
            self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_UPDATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $block_inners = array(
            self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_UPDATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_LOCATIONPOSTLINK],
            self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_CREATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_LOCATIONPOSTLINK],
        );
        if ($block_inner = $block_inners[$component->name] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_UPDATE:
            case self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_CREATE:
                $name = PoP_LocationPosts_PostNameUtils::getNameUc();
                if ($this->isUpdate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-locationpostlinkscreation-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_CREATE:
                return CreateLocationPostLinkMutationResolver::class;
            case self::COMPONENT_DATALOAD_LOCATIONPOSTLINK_UPDATE:
                return UpdateLocationPostLinkMutationResolver::class;
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}


