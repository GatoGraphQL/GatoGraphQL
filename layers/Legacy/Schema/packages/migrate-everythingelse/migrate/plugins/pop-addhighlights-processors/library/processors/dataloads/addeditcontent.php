<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\HighlightMutations\MutationResolverBridges\CreateHighlightMutationResolverBridge;
use PoPSitesWassup\HighlightMutations\MutationResolverBridges\UpdateHighlightMutationResolverBridge;
class PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const COMPONENT_DATALOAD_HIGHLIGHT_UPDATE = 'dataload-highlight-update';
    public final const COMPONENT_DATALOAD_HIGHLIGHT_CREATE = 'dataload-highlight-create';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_HIGHLIGHT_UPDATE,
            self::COMPONENT_DATALOAD_HIGHLIGHT_CREATE,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::COMPONENT_DATALOAD_HIGHLIGHT_UPDATE => POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_HIGHLIGHT_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($component, $props);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $block_inners = array(
            self::COMPONENT_DATALOAD_HIGHLIGHT_UPDATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_HIGHLIGHT],
            self::COMPONENT_DATALOAD_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_HIGHLIGHT],
        );
        if ($block_inner = $block_inners[$component->name] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_HIGHLIGHT_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_HIGHLIGHT_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_HIGHLIGHT_CREATE:
                return $this->instanceManager->getInstance(CreateHighlightMutationResolverBridge::class);
            case self::COMPONENT_DATALOAD_HIGHLIGHT_UPDATE:
                return $this->instanceManager->getInstance(UpdateHighlightMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_HIGHLIGHT_UPDATE:
            case self::COMPONENT_DATALOAD_HIGHLIGHT_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Highlight', 'pop-addhighlights-processors');
                if ($this->isUpdate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($component)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



