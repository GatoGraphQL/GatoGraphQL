<?php
define('GD_CONSTANT_FULLVIEW_TITLEPOSITION_TOP', 'top');
define('GD_CONSTANT_FULLVIEW_TITLEPOSITION_BODY', 'body');

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FullViewLayoutsBase extends PoP_Module_Processor_FullObjectLayoutsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FULLVIEW];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array_merge(
            parent::getDataFields($componentVariation, $props),
            array('catSlugs')
        );
    }

    public function titlePosition(array $componentVariation, array &$props)
    {
        return GD_CONSTANT_FULLVIEW_TITLEPOSITION_TOP;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($abovecontent_modules = $this->getAbovecontentSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $abovecontent_modules
            );
        }

        if ($content_modules = $this->getContentSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $content_modules
            );
        }

        return $ret;
    }

    public function getAbovecontentSubmodules(array $componentVariation)
    {
        return array();
    }

    public function getContentSubmodules(array $componentVariation)
    {
        return array(
            [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POSTFEED],
        );
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES]['title'] = '';

        if ($this->getTitleSubmodule($componentVariation, $props)) {
            $ret['title-position'] = $this->titlePosition($componentVariation, $props);
        }

        if ($abovecontent_modules = $this->getAbovecontentSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $abovecontent_modules
            );
        }

        if ($content_modules = $this->getContentSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['content'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $content_modules
            );
        }

        return $ret;
    }

    // function getJsmethods(array $componentVariation, array &$props) {

    //     $ret = parent::getJsmethods($componentVariation, $props);
    //     $this->addJsmethod($ret, 'waypointsHistoryStateNew');
    //     return $ret;
    // }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Make it waypoint
        $this->appendProp($componentVariation, $props, 'class', 'waypoint');

        // This one is independent of Waypoint because of the History, so here add them as separate instructions (just to make it clear)
        // $this->appendProp($componentVariation, $props, 'class', 'module-historystate newstate');
        parent::initModelProps($componentVariation, $props);
    }
}
