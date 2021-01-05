<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\HighlightMutations\MutationResolverBridges\CreateHighlightMutationResolverBridge;
use PoPSitesWassup\HighlightMutations\MutationResolverBridges\UpdateHighlightMutationResolverBridge;
class PoP_AddHighlights_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public const MODULE_DATALOAD_HIGHLIGHT_UPDATE = 'dataload-highlight-update';
    public const MODULE_DATALOAD_HIGHLIGHT_CREATE = 'dataload-highlight-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_HIGHLIGHT_UPDATE],
            [self::class, self::MODULE_DATALOAD_HIGHLIGHT_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_HIGHLIGHT_CREATE => POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            self::MODULE_DATALOAD_HIGHLIGHT_UPDATE => POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_CREATE:
                return GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_DATALOAD_HIGHLIGHT_UPDATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::MODULE_FORM_HIGHLIGHT],
            self::MODULE_DATALOAD_HIGHLIGHT_CREATE => [PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostForms::MODULE_FORM_HIGHLIGHT],
        );
        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_CREATE:
                return CreateHighlightMutationResolverBridge::class;
            case self::MODULE_DATALOAD_HIGHLIGHT_UPDATE:
                return UpdateHighlightMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_HIGHLIGHT_UPDATE:
            case self::MODULE_DATALOAD_HIGHLIGHT_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Highlight', 'pop-addhighlights-processors');
                if ($this->isUpdate($module)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($module)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



