<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;

trait FormatActiveTrait
{
    public function isSubmoduleActivePanel(array $module, $submodule)
    {
        return \PoP\Root\App::getState('format') == $this->getSubmoduleFormat($module, $submodule);
    }

    protected function getSubmoduleFormat(array $module, $submodule)
    {
        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
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
