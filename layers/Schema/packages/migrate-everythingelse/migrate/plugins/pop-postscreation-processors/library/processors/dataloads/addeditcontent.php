<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSitesWassup\PostMutations\MutationResolverBridges\CreatePostMutationResolverBridge;
use PoPSitesWassup\PostMutations\MutationResolverBridges\UpdatePostMutationResolverBridge;

class PoP_PostsCreation_Module_Processor_CreateUpdatePostDataloads extends PoP_Module_Processor_AddEditContentDataloadsBase
{
    public const MODULE_DATALOAD_POST_UPDATE = 'dataload-post-update';
    public const MODULE_DATALOAD_POST_CREATE = 'dataload-post-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_POST_UPDATE],
            [self::class, self::MODULE_DATALOAD_POST_CREATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_DATALOAD_POST_CREATE => POP_POSTSCREATION_ROUTE_ADDPOST,
            self::MODULE_DATALOAD_POST_UPDATE => POP_POSTSCREATION_ROUTE_EDITPOST,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_POST_CREATE:
                return GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS;
        }

        return parent::getRelevantRouteCheckpointTarget($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $block_inners = array(
            self::MODULE_DATALOAD_POST_UPDATE => [PoP_PostsCreation_Module_Processor_CreateUpdatePostForms::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_POST],
            self::MODULE_DATALOAD_POST_CREATE => [PoP_PostsCreation_Module_Processor_CreateUpdatePostForms::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostForms::MODULE_FORM_POST],
        );
        if ($block_inner = $block_inners[$module[1]]) {
            $ret[] = $block_inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_POST_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_POST_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_POST_CREATE:
                return CreatePostMutationResolverBridge::class;
            case self::MODULE_DATALOAD_POST_UPDATE:
                return UpdatePostMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_POST_UPDATE:
            case self::MODULE_DATALOAD_POST_CREATE:
                $name = TranslationAPIFacade::getInstance()->__('Post', 'pop-postscreation-processors');
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


