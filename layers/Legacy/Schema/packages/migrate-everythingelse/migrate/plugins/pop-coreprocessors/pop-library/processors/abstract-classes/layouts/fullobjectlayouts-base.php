<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FullObjectLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getSidebarSubmodule(array $component)
    {
        return null;
    }

    public function getTitleSubmodule(array $component)
    {
        return null;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array_merge(
            parent::getDataFields($component, $props),
            array('url')
        );
    }

    public function getHeaderSubmodules(array $component)
    {
        return array();
    }

    public function getFooterSubmodules(array $component)
    {
        return array();
    }

    public function getFullviewFooterSubmodules(array $component)
    {

        // Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
        return \PoP\Root\App::applyFilters('PoP_Module_Processor_FullObjectLayoutsBase:footer_components', $this->getFooterSubmodules($component), $component);
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($title = $this->getTitleSubmodule($component)) {
            $ret[] = $title;
        }
        if ($sidebar = $this->getSidebarSubmodule($component)) {
            $ret[] = $sidebar;
        }
        if ($headers = $this->getHeaderSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $headers
            );
        }
        if ($footers = $this->getFullviewFooterSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $footers
            );
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES] = array(
            'wrapper' => '',
            'inner-wrapper' => 'row',
            'socialmedia' => '',
            'content' => 'readable clearfix',
            'sidebar' => '',
            'content-body' => 'col-xs-12'

        );

        if ($title = $this->getTitleSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['title'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($title);
        }
        if ($sidebar = $this->getSidebarSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['sidebar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($sidebar);
            $ret[GD_JS_CLASSES]['sidebar'] = 'col-xsm-3 col-xsm-push-9';
            $ret[GD_JS_CLASSES]['content-body'] = 'col-xsm-9 col-xsm-pull-3';
        }
        if ($headers = $this->getHeaderSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['headers'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $headers
            );
        }
        if ($footers = $this->getFullviewFooterSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['footers'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $footers
            );
        }
        
        return $ret;
    }
}
