<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\PostLinkMutations\MutationResolverBridges\CreatePostLinkMutationResolverBridge;
use PoPSitesWassup\PostLinkMutations\MutationResolverBridges\UpdatePostLinkMutationResolverBridge;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE = 'dataload-postlink-update';
    public final const MODULE_DATALOAD_CONTENTPOSTLINK_CREATE = 'dataload-postlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE],
            [self::class, self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
            self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $block_inners = array(
            self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_CONTENTPOSTLINK],
            self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_CONTENTPOSTLINK],
        );
        if ($block_inner = $block_inners[$component[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Link', 'pop-userplatform-processors');
                if ($this->isUpdate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return $this->instanceManager->getInstance(CreatePostLinkMutationResolverBridge::class);
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
                return $this->instanceManager->getInstance(UpdatePostLinkMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}



