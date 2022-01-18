<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_MaxHeightLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MAXHEIGHT];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($inners = $this->getInnerSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $inners
            );
        }

        return $ret;
    }

    public function getMaxheight(array $module, array &$props)
    {
        return null;
    }

    // function getShowmoreBtnHtml(array $module, array &$props) {

    //     $titles = $this->getShowmoreBtnTitles($module, $props);
    //     return sprintf(
    //         '<div class="dynamicbtn-wrapper"><button class="js-dynamic-show-hide button %1$s" title="%2$s" data-replace-text="%3$s">%2$s</button></div>',
    //         $this->get_showmore_btn_class($module, $props),
    //         $titles['more'],
    //         $titles['less']
    //     );
    // }

    // function getShowmoreBtnTitles(array $module, array &$props) {

    //     return array(
    //         'more' => TranslationAPIFacade::getInstance()->__('Show more', 'pop-coreprocessors'),
    //         'less' => TranslationAPIFacade::getInstance()->__('Show less', 'pop-coreprocessors'),
    //     );
    // }
    public function getBtnTitles(array $module, array &$props)
    {
        return array(
            'more' => TranslationAPIFacade::getInstance()->__('Show more', 'pop-coreprocessors'),
            'less' => TranslationAPIFacade::getInstance()->__('Show less', 'pop-coreprocessors'),
        );
    }

    public function getBtnClass(array $module, array &$props)
    {
        return 'btn btn-link';
    }

    public function getInnerSubmodules(array $module): array
    {
        return array();
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // Integrate with plug-in "Dynamic max height plugin for jQuery"
        if (!is_null($this->getProp($module, $props, 'maxheight'))) {
            // This function is critical! Execute immediately, so users can already press on "See more" when scrolling down
            $this->addJsmethod($ret, 'dynamicMaxHeight', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        if ($inners = $this->getInnerSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $inners
            );
        }

        if ($btn_titles = $this->getBtnTitles($module, $props)) {
            $ret[GD_JS_TITLES] = $btn_titles;
        }
        if ($btn_class = $this->getBtnClass($module, $props)) {
            $ret[GD_JS_CLASSES]['btn'] = $btn_class;
        }

        $maxheight = $this->getProp($module, $props, 'maxheight');
        if (!is_null($maxheight)) {
            $ret['maxheight'] = $maxheight;
        }

        // // Integrate with plug-in "Dynamic max height plugin for jQuery"
        // if ($this->getProp($module, $props, 'maxheight')) {

        //     $ret[GD_JS_CLASSES]['inner'] = 'dynamic-height-wrap';
        //     $ret['description-bottom'] = $this->getShowmoreBtnHtml($module, $props);
        // }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'maxheight', $this->getMaxheight($module, $props));
        // $maxheight = $this->getProp($module, $props, 'maxheight');
        // if (!is_null($maxheight)) {

        //     // Integrate with plug-in "Dynamic max height plugin for jQuery"
        //     // $this->appendProp($module, $props, 'class', 'js-dynamic-height');
        //     $this->mergeProp($module, $props, 'params', array(
        //         'data-maxheight' => $maxheight,
        //     ));
        // }

        parent::initModelProps($module, $props);
    }
}
