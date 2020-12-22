<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_SegmentedButtonLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON];
    }

    public function getSegmentedbuttonSubmodules(array $module)
    {
        return array();
    }
    public function getDropdownsegmentedbuttonSubmodules(array $module)
    {
        return array();
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $ret = array_merge(
            $ret,
            $this->getSegmentedbuttonSubmodules($module),
            $this->getDropdownsegmentedbuttonSubmodules($module)
        );

        return $ret;
    }

    public function getBtnClass(array $module, array &$props)
    {
        return 'btn btn-default';
    }
    public function getCollapseClass(array $module)
    {
        return 'pop-showactive';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // Add the submoduleoutputnames of all blocks
        if (!$ret[GD_JS_SUBMODULEOUTPUTNAMES]) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES] = array();
        }

        $ret[GD_JS_TITLES]['toggle'] = TranslationAPIFacade::getInstance()->__('Toggle menu', 'pop-coreprocessors');
        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($module, $props);
        $ret[GD_JS_CLASSES]['collapse'] = $this->getCollapseClass($module);

        $segmentedbuttons = $this->getSegmentedbuttonSubmodules($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['segmentedbuttons'] = array_map(
            [ModuleUtils::class, 'getModuleOutputName'], 
            $segmentedbuttons
        );

        $segmentedbuttons = $this->getDropdownsegmentedbuttonSubmodules($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['dropdownsegmentedbuttons'] = array_map(
            [ModuleUtils::class, 'getModuleOutputName'], 
            $segmentedbuttons
        );

        return $ret;
    }
}
