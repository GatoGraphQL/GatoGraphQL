<?php
use PoP\Application\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Engine\Route\RouteUtils;

abstract class PoP_Module_Processor_BasicBlocksBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BASICBLOCK];
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if ($route = $this->getRelevantRoute($component, $props)) {
            return RouteUtils::getRouteTitle($route);
        }
        return null;
    }

    protected function getBlockTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // If the title has been set in the $props by a parent, use it
        // Otherwise, use the local module level. This bizarre solution is used, instead of directly
        // overriding the value of 'title' in the $props, since the title is dynamic (eg: $customPostTypeAPI->getPermalink($page))
        // however it is saved in the static cache. So then the assumption is that, if the title is set
        // from above, then it shall be static, otherwise this same level can be runtime
        return $this->getProp($component, $props, 'title') ?? $this->getTitle($component, $props);
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($block_inners = $this->getInnerSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $block_inners
            );
        }

        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($subcomponents = $this->getInnerSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['block-inners'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $subcomponents
            );
        }

        if ($description = $this->getProp($component, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        if ($title_htmltag = $this->getProp($component, $props, 'title-htmltag')) {
            $ret['title-htmltag'] = $title_htmltag;
        }

        if ($classes = $this->getBlocksectionsClasses($component)) {
            $ret[GD_JS_CLASSES] = $classes;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        if ($title = $this->getBlockTitle($component, $props)) {
            $ret['title'] = $title;

            if ($this->getProp($component, $props, 'add-titlelink')) {
                if ($title_link = $this->getTitleLink($component, $props)) {
                    $ret['title-link'] = $title_link;
                }
            }
        }

        return $ret;
    }

    public function getRequestPropsForDescendantComponents(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getRequestPropsForDescendantComponents($component, $props);

        if ($title = $this->getBlockTitle($component, $props)) {
            $ret['controltarget-title'] = $title;
        }

        return $ret;
    }

    protected function getTitleLink(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if ($route = $this->getRelevantRoute($component, $props)) {
            return RouteUtils::getRouteURL($route);
        }

        return null;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $this->appendProp($component, $props, 'class', 'pop-block');

        if ($description = $this->getDescription($component, $props)) {
            $this->setProp($component, $props, 'description', $description);
        }

        if ($title_htmltag = $this->getTitleHtmltag($component, $props)) {
            $this->setProp($component, $props, 'title-htmltag', $title_htmltag);
        }

        parent::initModelProps($component, $props);
    }

    protected function getDescription(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    protected function getTitleHtmltag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'h1';
    }

    protected function getBlocksectionsClasses(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getDataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($this->getProp($component, $props, 'do-not-render-if-no-results') && !(($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) && !$dbobjectids) {
            $ret['do-not-render'] = true;
        }

        return $ret;
    }
}
