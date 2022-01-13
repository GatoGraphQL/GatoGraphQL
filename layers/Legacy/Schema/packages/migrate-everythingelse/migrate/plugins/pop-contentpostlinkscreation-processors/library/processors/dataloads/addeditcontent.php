<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\PostLinkMutations\MutationResolverBridges\CreatePostLinkMutationResolverBridge;
use PoPSitesWassup\PostLinkMutations\MutationResolverBridges\UpdatePostLinkMutationResolverBridge;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public const MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE = 'dataload-postlink-update';
    public const MODULE_DATALOAD_CONTENTPOSTLINK_CREATE = 'dataload-postlink-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE],
            [self::class, self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
            self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_CONTENTPOSTLINK],
            self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_CONTENTPOSTLINK],
        );
        if ($block_inner = $block_inners[$module[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Link', 'pop-userplatform-processors');
                if ($this->isUpdate($module)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($module)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return $this->instanceManager->getInstance(CreatePostLinkMutationResolverBridge::class);
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
                return $this->instanceManager->getInstance(UpdatePostLinkMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }
}



