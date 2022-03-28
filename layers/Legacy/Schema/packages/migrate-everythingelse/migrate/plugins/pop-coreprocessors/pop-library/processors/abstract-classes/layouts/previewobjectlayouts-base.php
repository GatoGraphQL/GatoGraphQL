<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_PreviewObjectLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function showExcerpt(array $module)
    {
        return false;
    }

    public function getUrlField(array $module)
    {
        return 'url';
    }

    public function getTitleHtmlmarkup(array $module, array &$props)
    {
        return 'h4';
    }

    public function getLinktarget(array $module, array &$props)
    {
        return '';
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        return null;
    }
    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($module)) {
            $ret[] = $quicklinkgroup_top;
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($module)) {
            $ret[] = $quicklinkgroup_bottom;
        }


        return $ret;
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        $ret[] = $this->getUrlField($module);
        if ($this->showExcerpt($module)) {
            $ret[] = 'excerpt';
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES] = array();
        $ret['title-htmlmarkup'] = $this->getTitleHtmlmarkup($module, $props);
        $ret['url-field'] = $this->getUrlField($module);
        if ($this->showExcerpt($module)) {
            $ret['show-excerpt'] = true;
        }
        if ($target = $this->getLinktarget($module, $props)) {
            $ret['link-target'] = $target;
        }

        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_top);
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_bottom);
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Artificial property added to identify the module when adding module-resources
        $this->setProp($module, $props, 'resourceloader', 'layout');

        parent::initModelProps($module, $props);
    }
}
