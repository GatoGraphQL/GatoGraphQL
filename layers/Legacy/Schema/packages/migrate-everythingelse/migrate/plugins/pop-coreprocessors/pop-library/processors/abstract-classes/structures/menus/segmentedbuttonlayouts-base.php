<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_SegmentedButtonLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON];
    }

    public function getSegmentedbuttonSubcomponents(array $component)
    {
        return array();
    }
    public function getDropdownsegmentedbuttonSubcomponents(array $component)
    {
        return array();
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $ret = array_merge(
            $ret,
            $this->getSegmentedbuttonSubcomponents($component),
            $this->getDropdownsegmentedbuttonSubcomponents($component)
        );

        return $ret;
    }

    public function getBtnClass(array $component, array &$props)
    {
        return 'btn btn-default';
    }
    public function getCollapseClass(array $component)
    {
        return 'pop-showactive';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Add the subcomponentoutputnames of all blocks
        if (!$ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES] = array();
        }

        $ret[GD_JS_TITLES]['toggle'] = TranslationAPIFacade::getInstance()->__('Toggle menu', 'pop-coreprocessors');
        $ret[GD_JS_CLASSES]['btn'] = $this->getBtnClass($component, $props);
        $ret[GD_JS_CLASSES]['collapse'] = $this->getCollapseClass($component);

        $segmentedbuttons = $this->getSegmentedbuttonSubcomponents($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['segmentedbuttons'] = array_map(
            [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
            $segmentedbuttons
        );

        $segmentedbuttons = $this->getDropdownsegmentedbuttonSubcomponents($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['dropdownsegmentedbuttons'] = array_map(
            [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
            $segmentedbuttons
        );

        return $ret;
    }
}
