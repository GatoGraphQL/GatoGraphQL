<?php
define('GD_CONSTANT_FULLVIEW_TITLEPOSITION_TOP', 'top');
define('GD_CONSTANT_FULLVIEW_TITLEPOSITION_BODY', 'body');

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_FullViewLayoutsBase extends PoP_Module_Processor_FullObjectLayoutsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FULLVIEW];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array_merge(
            parent::getDataFields($module, $props),
            array('catSlugs')
        );
    }

    public function titlePosition(array $module, array &$props)
    {
        return GD_CONSTANT_FULLVIEW_TITLEPOSITION_TOP;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($abovecontent_modules = $this->getAbovecontentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $abovecontent_modules
            );
        }

        if ($content_modules = $this->getContentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $content_modules
            );
        }

        return $ret;
    }

    public function getAbovecontentSubmodules(array $module)
    {
        return array();
    }

    public function getContentSubmodules(array $module)
    {
        return array(
            [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POSTFEED],
        );
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES]['title'] = '';

        if ($this->getTitleSubmodule($module, $props)) {
            $ret['title-position'] = $this->titlePosition($module, $props);
        }

        if ($abovecontent_modules = $this->getAbovecontentSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $abovecontent_modules
            );
        }

        if ($content_modules = $this->getContentSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['content'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $content_modules
            );
        }

        return $ret;
    }

    // function getJsmethods(array $module, array &$props) {

    //     $ret = parent::getJsmethods($module, $props);
    //     $this->addJsmethod($ret, 'waypointsHistoryStateNew');
    //     return $ret;
    // }

    public function initModelProps(array $module, array &$props): void
    {

        // Make it waypoint
        $this->appendProp($module, $props, 'class', 'waypoint');

        // This one is independent of Waypoint because of the History, so here add them as separate instructions (just to make it clear)
        // $this->appendProp($module, $props, 'class', 'module-historystate newstate');
        parent::initModelProps($module, $props);
    }
}
