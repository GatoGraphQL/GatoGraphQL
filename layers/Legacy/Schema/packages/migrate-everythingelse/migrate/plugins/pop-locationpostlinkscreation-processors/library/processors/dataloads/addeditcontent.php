<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\LocationPostLinkMutations\MutationResolvers\CreateLocationPostLinkMutationResolver;
use PoPSitesWassup\LocationPostLinkMutations\MutationResolvers\UpdateLocationPostLinkMutationResolver;

class PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const MODULE_DATALOAD_LOCATIONPOSTLINK_UPDATE = 'dataload-locationpostlink-update';
    public final const MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE = 'dataload-locationpostlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTLINK_UPDATE],
            [self::class, self::MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
            self::MODULE_DATALOAD_LOCATIONPOSTLINK_UPDATE => POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::MODULE_DATALOAD_LOCATIONPOSTLINK_UPDATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::MODULE_FORM_LOCATIONPOSTLINK],
            self::MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE => [GD_Custom_EM_Module_Processor_CreateUpdatePostForms::class, GD_Custom_EM_Module_Processor_CreateUpdatePostForms::MODULE_FORM_LOCATIONPOSTLINK],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOSTLINK_UPDATE:
            case self::MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE:
                $name = PoP_LocationPosts_PostNameUtils::getNameUc();
                if ($this->isUpdate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-locationpostlinkscreation-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LOCATIONPOSTLINK_CREATE:
                return CreateLocationPostLinkMutationResolver::class;
            case self::MODULE_DATALOAD_LOCATIONPOSTLINK_UPDATE:
                return UpdateLocationPostLinkMutationResolver::class;
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}


