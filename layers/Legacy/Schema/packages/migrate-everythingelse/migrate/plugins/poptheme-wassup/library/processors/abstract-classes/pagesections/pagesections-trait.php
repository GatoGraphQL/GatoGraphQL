<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait PoPTheme_Wassup_Module_Processor_PageSectionsTrait
{
    public function getExtraTemplateResources(array $componentVariation, array &$props): array
    {
        // Add the extension templates
        $ret = parent::getExtraTemplateResources($componentVariation, $props);
        $ret['extensions'] = $ret['extensions'] ?? array();
        $ret['extensions'][] = [PoPTheme_Wassup_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONPAGESECTIONFRAMEOPTIONS];
        return $ret;
    }

    protected function getFrameoptionsSubmodules(array $componentVariation): array
    {
        return array_merge(
            $this->getFrametopoptionsSubmodules($componentVariation),
            $this->getFramebottomoptionsSubmodules($componentVariation)
        );
    }

    public function getFrametopoptionsSubmodules(array $componentVariation): array
    {
        return array();
    }

    public function getFramebottomoptionsSubmodules(array $componentVariation): array
    {
        return array();
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        return array_merge(
            parent::getSubComponentVariations($componentVariation),
            $this->getFrameoptionsSubmodules($componentVariation)
        );
    }

    public function getMutableonmodelConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getMutableonmodelConfiguration($componentVariation, $props);

        if ($subComponentVariations = $this->getFrameoptionsSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['frameoptions'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $subComponentVariations
            );
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // All blocks added under the pageSection can have class "pop-outerblock"
        foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {
            $this->appendProp([$subComponentVariation], $props, 'class', 'pop-outerblock');
        }

        $topframeoptions = $this->getFrametopoptionsSubmodules($componentVariation);
        $bottomframeoptions = $this->getFramebottomoptionsSubmodules($componentVariation);
        foreach ($this->getFrameoptionsSubmodules($componentVariation) as $subComponentVariation) {
            $this->appendProp([$subComponentVariation], $props, 'class', 'blocksection-controls pull-right');

            if (in_array($subComponentVariation, $topframeoptions)) {
                $this->appendProp([$subComponentVariation], $props, 'class', 'top');
            } elseif (in_array($subComponentVariation, $bottomframeoptions)) {
                $this->appendProp([$subComponentVariation], $props, 'class', 'bottom');
            }
        }

        parent::initModelProps($componentVariation, $props);
    }
}
