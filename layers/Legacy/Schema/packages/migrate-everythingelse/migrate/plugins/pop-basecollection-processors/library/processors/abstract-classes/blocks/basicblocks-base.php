<?php
use PoP\Application\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Engine\Route\RouteUtils;

abstract class PoP_Module_Processor_BasicBlocksBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BASICBLOCK];
    }

    protected function getInnerSubmodules(array $module): array
    {
        return array();
    }

    public function getTitle(array $module, array &$props)
    {
        if ($route = $this->getRelevantRoute($module, $props)) {
            return RouteUtils::getRouteTitle($route);
        }
        return null;
    }

    protected function getBlockTitle(array $module, array &$props)
    {

        // If the title has been set in the $props by a parent, use it
        // Otherwise, use the local module level. This bizarre solution is used, instead of directly
        // overriding the value of 'title' in the $props, since the title is dynamic (eg: $customPostTypeAPI->getPermalink($page))
        // however it is saved in the static cache. So then the assumption is that, if the title is set
        // from above, then it shall be static, otherwise this same level can be runtime
        return $this->getProp($module, $props, 'title') ?? $this->getTitle($module, $props);
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($block_inners = $this->getInnerSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $block_inners
            );
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($submodules = $this->getInnerSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['block-inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $submodules
            );
        }

        if ($description = $this->getProp($module, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        if ($title_htmltag = $this->getProp($module, $props, 'title-htmltag')) {
            $ret['title-htmltag'] = $title_htmltag;
        }

        if ($classes = $this->getBlocksectionsClasses($module)) {
            $ret[GD_JS_CLASSES] = $classes;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        if ($title = $this->getBlockTitle($module, $props)) {
            $ret['title'] = $title;

            if ($this->getProp($module, $props, 'add-titlelink')) {
                if ($title_link = $this->getTitleLink($module, $props)) {
                    $ret['title-link'] = $title_link;
                }
            }
        }

        return $ret;
    }

    public function getRequestPropsForDescendantModules(array $module, array &$props): array
    {
        $ret = parent::getRequestPropsForDescendantModules($module, $props);

        if ($title = $this->getBlockTitle($module, $props)) {
            $ret['controltarget-title'] = $title;
        }

        return $ret;
    }

    protected function getTitleLink(array $module, array &$props)
    {
        if ($route = $this->getRelevantRoute($module, $props)) {
            return RouteUtils::getRouteURL($route);
        }

        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $this->appendProp($module, $props, 'class', 'pop-block');

        if ($description = $this->getDescription($module, $props)) {
            $this->setProp($module, $props, 'description', $description);
        }

        if ($title_htmltag = $this->getTitleHtmltag($module, $props)) {
            $this->setProp($module, $props, 'title-htmltag', $title_htmltag);
        }

        parent::initModelProps($module, $props);
    }

    protected function getDescription(array $module, array &$props)
    {
        return null;
    }

    protected function getTitleHtmltag(array $module, array &$props)
    {
        return 'h1';
    }

    protected function getBlocksectionsClasses(array $module)
    {
        return array();
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = parent::getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($this->getProp($module, $props, 'do-not-render-if-no-results') && !(($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) && !$dbobjectids) {
            $ret['do-not-render'] = true;
        }

        return $ret;
    }
}
