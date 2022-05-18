<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_SegmentedButtonLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON];
    }

    public function getSegmentedbuttonSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getDropdownsegmentedbuttonSubmodules(array $componentVariation)
    {
        return array();
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $ret = array_merge(
            $ret,
            $this->getSegmentedbuttonSubmodules($componentVariation),
            $this->getDropdownsegmentedbuttonSubmodules($componentVariation)
        );

        return $ret;
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        return 'btn btn-default';
    }
    public function getCollapseClass(array $componentVariation)
    {
        return 'pop-showactive';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Add the submoduleoutputnames of all blocks
        if (!$ret[GD_JS_SUBMODULEOUTPUTNAMES]) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES] = array();
        }

        $ret[GD_JS_TITLES]['toggle'] = TranslationAPIFacade::getInstance()->__('Toggle menu', 'pop-coreprocessors');
        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($componentVariation, $props);
        $ret[GD_JS_CLASSES]['collapse'] = $this->getCollapseClass($componentVariation);

        $segmentedbuttons = $this->getSegmentedbuttonSubmodules($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['segmentedbuttons'] = array_map(
            [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
            $segmentedbuttons
        );

        $segmentedbuttons = $this->getDropdownsegmentedbuttonSubmodules($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['dropdownsegmentedbuttons'] = array_map(
            [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
            $segmentedbuttons
        );

        return $ret;
    }
}
