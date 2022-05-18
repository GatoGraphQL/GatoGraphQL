<?php
use PoP\Application\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Engine\Route\RouteUtils;

abstract class PoP_Module_Processor_BasicBlocksBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BASICBLOCK];
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        return array();
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        if ($route = $this->getRelevantRoute($componentVariation, $props)) {
            return RouteUtils::getRouteTitle($route);
        }
        return null;
    }

    protected function getBlockTitle(array $componentVariation, array &$props)
    {

        // If the title has been set in the $props by a parent, use it
        // Otherwise, use the local module level. This bizarre solution is used, instead of directly
        // overriding the value of 'title' in the $props, since the title is dynamic (eg: $customPostTypeAPI->getPermalink($page))
        // however it is saved in the static cache. So then the assumption is that, if the title is set
        // from above, then it shall be static, otherwise this same level can be runtime
        return $this->getProp($componentVariation, $props, 'title') ?? $this->getTitle($componentVariation, $props);
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($block_inners = $this->getInnerSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $block_inners
            );
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($subComponentVariations = $this->getInnerSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['block-inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $subComponentVariations
            );
        }

        if ($description = $this->getProp($componentVariation, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        if ($title_htmltag = $this->getProp($componentVariation, $props, 'title-htmltag')) {
            $ret['title-htmltag'] = $title_htmltag;
        }

        if ($classes = $this->getBlocksectionsClasses($componentVariation)) {
            $ret[GD_JS_CLASSES] = $classes;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        if ($title = $this->getBlockTitle($componentVariation, $props)) {
            $ret['title'] = $title;

            if ($this->getProp($componentVariation, $props, 'add-titlelink')) {
                if ($title_link = $this->getTitleLink($componentVariation, $props)) {
                    $ret['title-link'] = $title_link;
                }
            }
        }

        return $ret;
    }

    public function getRequestPropsForDescendantComponentVariations(array $componentVariation, array &$props): array
    {
        $ret = parent::getRequestPropsForDescendantComponentVariations($componentVariation, $props);

        if ($title = $this->getBlockTitle($componentVariation, $props)) {
            $ret['controltarget-title'] = $title;
        }

        return $ret;
    }

    protected function getTitleLink(array $componentVariation, array &$props)
    {
        if ($route = $this->getRelevantRoute($componentVariation, $props)) {
            return RouteUtils::getRouteURL($route);
        }

        return null;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $this->appendProp($componentVariation, $props, 'class', 'pop-block');

        if ($description = $this->getDescription($componentVariation, $props)) {
            $this->setProp($componentVariation, $props, 'description', $description);
        }

        if ($title_htmltag = $this->getTitleHtmltag($componentVariation, $props)) {
            $this->setProp($componentVariation, $props, 'title-htmltag', $title_htmltag);
        }

        parent::initModelProps($componentVariation, $props);
    }

    protected function getDescription(array $componentVariation, array &$props)
    {
        return null;
    }

    protected function getTitleHtmltag(array $componentVariation, array &$props)
    {
        return 'h1';
    }

    protected function getBlocksectionsClasses(array $componentVariation)
    {
        return array();
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getDataFeedback($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($this->getProp($componentVariation, $props, 'do-not-render-if-no-results') && !(($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) && !$dbobjectids) {
            $ret['do-not-render'] = true;
        }

        return $ret;
    }
}
