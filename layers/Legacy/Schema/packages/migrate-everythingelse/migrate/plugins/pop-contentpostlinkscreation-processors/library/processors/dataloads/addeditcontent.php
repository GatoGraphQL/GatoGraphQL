<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\PostLinkMutations\MutationResolverBridges\CreatePostLinkMutationResolverBridge;
use PoPSitesWassup\PostLinkMutations\MutationResolverBridges\UpdatePostLinkMutationResolverBridge;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE = 'dataload-postlink-update';
    public final const MODULE_DATALOAD_CONTENTPOSTLINK_CREATE = 'dataload-postlink-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE],
            [self::class, self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
            self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE => POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($componentVariation, $props);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $block_inners = array(
            self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_CONTENTPOSTLINK],
            self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_CONTENTPOSTLINK],
        );
        if ($block_inner = $block_inners[$componentVariation[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return true;
        }

        return parent::isCreate($componentVariation);
    }
    protected function isUpdate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
                return true;
        }

        return parent::isUpdate($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Link', 'pop-userplatform-processors');
                if ($this->isUpdate($componentVariation)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT], $props, 'objectname', $name);
                } elseif ($this->isCreate($componentVariation)) {
                    $this->setProp([PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT], $props, 'objectname', $name);
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_CREATE:
                return $this->instanceManager->getInstance(CreatePostLinkMutationResolverBridge::class);
            case self::MODULE_DATALOAD_CONTENTPOSTLINK_UPDATE:
                return $this->instanceManager->getInstance(UpdatePostLinkMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }
}



