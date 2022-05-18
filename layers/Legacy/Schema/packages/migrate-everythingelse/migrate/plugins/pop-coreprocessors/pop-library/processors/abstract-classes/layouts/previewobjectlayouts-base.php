<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PreviewObjectLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function showExcerpt(array $componentVariation)
    {
        return false;
    }

    public function getUrlField(array $componentVariation)
    {
        return 'url';
    }

    public function getTitleHtmlmarkup(array $componentVariation, array &$props)
    {
        return 'h4';
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getQuicklinkgroupTopSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getQuicklinkgroupBottomSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($componentVariation)) {
            $ret[] = $quicklinkgroup_top;
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($componentVariation)) {
            $ret[] = $quicklinkgroup_bottom;
        }


        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);

        $ret[] = $this->getUrlField($componentVariation);
        if ($this->showExcerpt($componentVariation)) {
            $ret[] = 'excerpt';
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES] = array();
        $ret['title-htmlmarkup'] = $this->getTitleHtmlmarkup($componentVariation, $props);
        $ret['url-field'] = $this->getUrlField($componentVariation);
        if ($this->showExcerpt($componentVariation)) {
            $ret['show-excerpt'] = true;
        }
        if ($target = $this->getLinktarget($componentVariation, $props)) {
            $ret['link-target'] = $target;
        }

        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_top);
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_bottom);
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Artificial property added to identify the module when adding module-resources
        $this->setProp($componentVariation, $props, 'resourceloader', 'layout');

        parent::initModelProps($componentVariation, $props);
    }
}
