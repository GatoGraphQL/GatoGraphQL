<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_MaxHeightLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MAXHEIGHT];
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($inners = $this->getInnerSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $inners
            );
        }

        return $ret;
    }

    public function getMaxheight(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    // function getShowmoreBtnHtml(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     $titles = $this->getShowmoreBtnTitles($component, $props);
    //     return sprintf(
    //         '<div class="dynamicbtn-wrapper"><button class="js-dynamic-show-hide button %1$s" title="%2$s" data-replace-text="%3$s">%2$s</button></div>',
    //         $this->get_showmore_btn_class($component, $props),
    //         $titles['more'],
    //         $titles['less']
    //     );
    // }

    // function getShowmoreBtnTitles(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     return array(
    //         'more' => TranslationAPIFacade::getInstance()->__('Show more', 'pop-coreprocessors'),
    //         'less' => TranslationAPIFacade::getInstance()->__('Show less', 'pop-coreprocessors'),
    //     );
    // }
    public function getBtnTitles(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array(
            'more' => TranslationAPIFacade::getInstance()->__('Show more', 'pop-coreprocessors'),
            'less' => TranslationAPIFacade::getInstance()->__('Show less', 'pop-coreprocessors'),
        );
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'btn btn-link';
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Integrate with plug-in "Dynamic max height plugin for jQuery"
        if (!is_null($this->getProp($component, $props, 'maxheight'))) {
            // This function is critical! Execute immediately, so users can already press on "See more" when scrolling down
            $this->addJsmethod($ret, 'dynamicMaxHeight', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
        }

        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        if ($inners = $this->getInnerSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['inners'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $inners
            );
        }

        if ($btn_titles = $this->getBtnTitles($component, $props)) {
            $ret[GD_JS_TITLES] = $btn_titles;
        }
        if ($btn_class = $this->getBtnClass($component, $props)) {
            $ret[GD_JS_CLASSES]['btn'] = $btn_class;
        }

        $maxheight = $this->getProp($component, $props, 'maxheight');
        if (!is_null($maxheight)) {
            $ret['maxheight'] = $maxheight;
        }

        // // Integrate with plug-in "Dynamic max height plugin for jQuery"
        // if ($this->getProp($component, $props, 'maxheight')) {

        //     $ret[GD_JS_CLASSES]['inner'] = 'dynamic-height-wrap';
        //     $ret['description-bottom'] = $this->getShowmoreBtnHtml($component, $props);
        // }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->setProp($component, $props, 'maxheight', $this->getMaxheight($component, $props));
        // $maxheight = $this->getProp($component, $props, 'maxheight');
        // if (!is_null($maxheight)) {

        //     // Integrate with plug-in "Dynamic max height plugin for jQuery"
        //     // $this->appendProp($component, $props, 'class', 'js-dynamic-height');
        //     $this->mergeProp($component, $props, 'params', array(
        //         'data-maxheight' => $maxheight,
        //     ));
        // }

        parent::initModelProps($component, $props);
    }
}
