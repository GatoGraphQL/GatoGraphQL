<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_FullObjectLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getSidebarSubmodule(array $module)
    {
        return null;
    }

    public function getTitleSubmodule(array $module)
    {
        return null;
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
            array('url')
        );
    }

    public function getHeaderSubmodules(array $module)
    {
        return array();
    }

    public function getFooterSubmodules(array $module)
    {
        return array();
    }

    public function getFullviewFooterSubmodules(array $module)
    {

        // Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
        return \PoP\Root\App::applyFilters('PoP_Module_Processor_FullObjectLayoutsBase:footer_modules', $this->getFooterSubmodules($module), $module);
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($title = $this->getTitleSubmodule($module)) {
            $ret[] = $title;
        }
        if ($sidebar = $this->getSidebarSubmodule($module)) {
            $ret[] = $sidebar;
        }
        if ($headers = $this->getHeaderSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $headers
            );
        }
        if ($footers = $this->getFullviewFooterSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $footers
            );
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES] = array(
            'wrapper' => '',
            'inner-wrapper' => 'row',
            'socialmedia' => '',
            'content' => 'readable clearfix',
            'sidebar' => '',
            'content-body' => 'col-xs-12'

        );

        if ($title = $this->getTitleSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['title'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($title);
        }
        if ($sidebar = $this->getSidebarSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['sidebar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($sidebar);
            $ret[GD_JS_CLASSES]['sidebar'] = 'col-xsm-3 col-xsm-push-9';
            $ret[GD_JS_CLASSES]['content-body'] = 'col-xsm-9 col-xsm-pull-3';
        }
        if ($headers = $this->getHeaderSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['headers'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $headers
            );
        }
        if ($footers = $this->getFullviewFooterSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['footers'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $footers
            );
        }
        
        return $ret;
    }
}
