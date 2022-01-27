<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;

trait FormatActiveTrait
{
    public function isSubmoduleActivePanel(array $module, $submodule)
    {
        return \PoP\Root\App::getState('format') == $this->getSubmoduleFormat($module, $submodule);
    }

    protected function getSubmoduleFormat(array $module, $submodule)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($submodule);
        if ($processor instanceof FormattableModuleInterface) {
            return $processor->getFormat($submodule);
        }
    
        return null;
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        return null;
    }

    public function getDefaultActivepanelSubmodule(array $module)
    {
        if ($default_format = $this->getDefaultActivepanelFormat($module)) {
            foreach ($this->getSubmodules($module) as $submodule) {
                if ($default_format == $this->getSubmoduleFormat($module, $submodule)) {
                    return $submodule;
                }
            }
        }

        return parent::getDefaultActivepanelSubmodule($module);
    }
}
