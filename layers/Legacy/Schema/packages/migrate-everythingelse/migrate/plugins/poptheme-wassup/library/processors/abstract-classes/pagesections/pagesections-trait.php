<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

trait PoPTheme_Wassup_Module_Processor_PageSectionsTrait
{
    public function getExtraTemplateResources(array $module, array &$props): array
    {
        // Add the extension templates
        $ret = parent::getExtraTemplateResources($module, $props);
        $ret['extensions'] = $ret['extensions'] ?? array();
        $ret['extensions'][] = [PoPTheme_Wassup_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONPAGESECTIONFRAMEOPTIONS];
        return $ret;
    }

    protected function getFrameoptionsSubmodules(array $module): array
    {
        return array_merge(
            $this->getFrametopoptionsSubmodules($module),
            $this->getFramebottomoptionsSubmodules($module)
        );
    }

    public function getFrametopoptionsSubmodules(array $module): array
    {
        return array();
    }

    public function getFramebottomoptionsSubmodules(array $module): array
    {
        return array();
    }

    public function getSubmodules(array $module): array
    {
        return array_merge(
            parent::getSubmodules($module),
            $this->getFrameoptionsSubmodules($module)
        );
    }

    public function getMutableonmodelConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getMutableonmodelConfiguration($module, $props);

        if ($submodules = $this->getFrameoptionsSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['frameoptions'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $submodules
            );
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function initModelProps(array $module, array &$props): void
    {

        // All blocks added under the pageSection can have class "pop-outerblock"
        foreach ($this->getSubmodules($module) as $submodule) {
            $this->appendProp([$submodule], $props, 'class', 'pop-outerblock');
        }

        $topframeoptions = $this->getFrametopoptionsSubmodules($module);
        $bottomframeoptions = $this->getFramebottomoptionsSubmodules($module);
        foreach ($this->getFrameoptionsSubmodules($module) as $submodule) {
            $this->appendProp([$submodule], $props, 'class', 'blocksection-controls pull-right');

            if (in_array($submodule, $topframeoptions)) {
                $this->appendProp([$submodule], $props, 'class', 'top');
            } elseif (in_array($submodule, $bottomframeoptions)) {
                $this->appendProp([$submodule], $props, 'class', 'bottom');
            }
        }

        parent::initModelProps($module, $props);
    }
}
