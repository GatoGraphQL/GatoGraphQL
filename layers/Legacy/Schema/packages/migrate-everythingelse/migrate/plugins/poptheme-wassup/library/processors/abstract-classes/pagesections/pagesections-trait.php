<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait PoPTheme_Wassup_Module_Processor_PageSectionsTrait
{
    public function getExtraTemplateResources(array $component, array &$props): array
    {
        // Add the extension templates
        $ret = parent::getExtraTemplateResources($component, $props);
        $ret['extensions'] = $ret['extensions'] ?? array();
        $ret['extensions'][] = [PoPTheme_Wassup_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONPAGESECTIONFRAMEOPTIONS];
        return $ret;
    }

    protected function getFrameoptionsSubmodules(array $component): array
    {
        return array_merge(
            $this->getFrametopoptionsSubmodules($component),
            $this->getFramebottomoptionsSubmodules($component)
        );
    }

    public function getFrametopoptionsSubmodules(array $component): array
    {
        return array();
    }

    public function getFramebottomoptionsSubmodules(array $component): array
    {
        return array();
    }

    public function getSubcomponents(array $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getFrameoptionsSubmodules($component)
        );
    }

    public function getMutableonmodelConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getMutableonmodelConfiguration($component, $props);

        if ($subComponents = $this->getFrameoptionsSubmodules($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['frameoptions'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $subComponents
            );
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function initModelProps(array $component, array &$props): void
    {

        // All blocks added under the pageSection can have class "pop-outerblock"
        foreach ($this->getSubcomponents($component) as $subComponent) {
            $this->appendProp([$subComponent], $props, 'class', 'pop-outerblock');
        }

        $topframeoptions = $this->getFrametopoptionsSubmodules($component);
        $bottomframeoptions = $this->getFramebottomoptionsSubmodules($component);
        foreach ($this->getFrameoptionsSubmodules($component) as $subComponent) {
            $this->appendProp([$subComponent], $props, 'class', 'blocksection-controls pull-right');

            if (in_array($subComponent, $topframeoptions)) {
                $this->appendProp([$subComponent], $props, 'class', 'top');
            } elseif (in_array($subComponent, $bottomframeoptions)) {
                $this->appendProp([$subComponent], $props, 'class', 'bottom');
            }
        }

        parent::initModelProps($component, $props);
    }
}
