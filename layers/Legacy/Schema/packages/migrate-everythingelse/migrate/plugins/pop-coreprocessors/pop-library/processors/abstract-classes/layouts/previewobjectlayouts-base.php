<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PreviewObjectLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function showExcerpt(array $component)
    {
        return false;
    }

    public function getUrlField(array $component)
    {
        return 'url';
    }

    public function getTitleHtmlmarkup(array $component, array &$props)
    {
        return 'h4';
    }

    public function getLinktarget(array $component, array &$props)
    {
        return '';
    }

    public function getQuicklinkgroupTopSubcomponent(array $component)
    {
        return null;
    }
    public function getQuicklinkgroupBottomSubcomponent(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubcomponent($component)) {
            $ret[] = $quicklinkgroup_top;
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubcomponent($component)) {
            $ret[] = $quicklinkgroup_bottom;
        }


        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        $ret[] = $this->getUrlField($component);
        if ($this->showExcerpt($component)) {
            $ret[] = 'excerpt';
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES] = array();
        $ret['title-htmlmarkup'] = $this->getTitleHtmlmarkup($component, $props);
        $ret['url-field'] = $this->getUrlField($component);
        if ($this->showExcerpt($component)) {
            $ret['show-excerpt'] = true;
        }
        if ($target = $this->getLinktarget($component, $props)) {
            $ret['link-target'] = $target;
        }

        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['quicklinkgroup-top'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_top);
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['quicklinkgroup-bottom'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_bottom);
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Artificial property added to identify the module when adding component-resources
        $this->setProp($component, $props, 'resourceloader', 'layout');

        parent::initModelProps($component, $props);
    }
}
