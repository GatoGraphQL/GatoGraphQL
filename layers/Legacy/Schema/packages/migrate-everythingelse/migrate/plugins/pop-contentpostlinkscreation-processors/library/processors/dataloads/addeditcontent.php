<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\PostLinkMutations\MutationResolverBridges\CreatePostLinkMutationResolverBridge;
use PoPSitesWassup\PostLinkMutations\MutationResolverBridges\UpdatePostLinkMutationResolverBridge;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const COMPONENT_DATALOAD_CONTENTPOSTLINK_UPDATE = 'dataload-postlink-update';
    public final const COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE = 'dataload-postlink-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_CONTENTPOSTLINK_UPDATE],
            [self::class, self::COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
            self::COMPONENT_DATALOAD_CONTENTPOSTLINK_UPDATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $block_inners = array(
            self::COMPONENT_DATALOAD_CONTENTPOSTLINK_UPDATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_CONTENTPOSTLINK],
            self::COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_CONTENTPOSTLINK],
        );
        if ($block_inner = $block_inners[$component->name] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CONTENTPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CONTENTPOSTLINK_UPDATE:
            case self::COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Link', 'pop-userplatform-processors');
                if ($this->isUpdate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_CONTENTPOSTLINK_CREATE:
                return $this->instanceManager->getInstance(CreatePostLinkMutationResolverBridge::class);
            case self::COMPONENT_DATALOAD_CONTENTPOSTLINK_UPDATE:
                return $this->instanceManager->getInstance(UpdatePostLinkMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}



