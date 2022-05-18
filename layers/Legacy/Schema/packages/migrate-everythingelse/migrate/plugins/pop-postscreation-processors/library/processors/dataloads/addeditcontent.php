<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSitesWassup\PostMutations\MutationResolverBridges\CreatePostMutationResolverBridge;
use PoPSitesWassup\PostMutations\MutationResolverBridges\UpdatePostMutationResolverBridge;

class PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public final const MODULE_DATALOAD_POST_UPDATE = 'dataload-post-update';
    public final const MODULE_DATALOAD_POST_CREATE = 'dataload-post-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_POST_UPDATE],
            [self::class, self::MODULE_DATALOAD_POST_CREATE],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_POST_CREATE => POP_POSTSCREATION_ROUTE_ADDPOST,
            self::MODULE_DATALOAD_POST_UPDATE => POP_POSTSCREATION_ROUTE_EDITPOST,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_POST_CREATE:
                return \PoP\ComponentModel\Constants\DataLoading::ACTION_EXECUTION_CHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($componentVariation, $props);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $block_inners = array(
            self::MODULE_DATALOAD_POST_UPDATE => [PoP_PostsCreation_Module_Processor_CreateUpdatePostForms::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_POST],
            self::MODULE_DATALOAD_POST_CREATE => [PoP_PostsCreation_Module_Processor_CreateUpdatePostForms::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_POST],
        );
        if ($block_inner = $block_inners[$componentVariation[1]] ?? null) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_POST_CREATE:
                return true;
        }

        return parent::isCreate($componentVariation);
    }
    protected function isUpdate(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_POST_UPDATE:
                return true;
        }

        return parent::isUpdate($componentVariation);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_POST_CREATE:
                return $this->instanceManager->getInstance(CreatePostMutationResolverBridge::class);
            case self::MODULE_DATALOAD_POST_UPDATE:
                return $this->instanceManager->getInstance(UpdatePostMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_POST_UPDATE:
            case self::MODULE_DATALOAD_POST_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Post', 'pop-postscreation-processors');
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


