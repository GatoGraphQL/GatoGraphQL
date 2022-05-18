<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\HighlightMutations\MutationResolverBridges\CreateHighlightMutationResolverBridge;
use PoPSitesWassup\HighlightMutations\MutationResolverBridges\UpdateHighlightMutationResolverBridge;
class PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const MODULE_DATALOAD_HIGHLIGHT_UPDATE = 'dataload-highlight-update';
    public final const MODULE_DATALOAD_HIGHLIGHT_CREATE = 'dataload-highlight-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHT_UPDATE],
            [self::class, self::MODULE_DATALOAD_HIGHLIGHT_CREATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::MODULE_DATALOAD_HIGHLIGHT_UPDATE => POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($componentVariation, $props);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $block_inners = array(
            self::MODULE_DATALOAD_HIGHLIGHT_UPDATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::MODULE_FORM_HIGHLIGHT],
            self::MODULE_DATALOAD_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::MODULE_FORM_HIGHLIGHT],
        );
        if ($block_inner = $block_inners[$componentVariation[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_CREATE:
                return true;
        }

        return parent::isCreate($componentVariation);
    }
    protected function isUpdate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_UPDATE:
                return true;
        }

        return parent::isUpdate($componentVariation);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_CREATE:
                return $this->instanceManager->getInstance(CreateHighlightMutationResolverBridge::class);
            case self::MODULE_DATALOAD_HIGHLIGHT_UPDATE:
                return $this->instanceManager->getInstance(UpdateHighlightMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_UPDATE:
            case self::MODULE_DATALOAD_HIGHLIGHT_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Highlight', 'pop-addhighlights-processors');
                if ($this->isUpdate($componentVariation)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($componentVariation)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



