<?php
namespace PoP\Engine;

abstract class ModuleDecoratorProcessorBase
{
    use ModulePathProcessorTrait;

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getModuleProcessor($module)
    {
        return $this->getModuleProcessordecorator($module);
    }

    protected function getModuleProcessordecorator($module)
    {
        $processor = $this->getDecoratedmoduleProcessor($module);
        return $this->getModuledecoratorprocessorManager()->getProcessordecorator($processor);
    }

    protected function getDecoratedmoduleProcessor($module)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        return $moduleprocessor_manager->getProcessor($module);
    }

    protected function getModuledecoratorprocessorManager()
    {
        return null;
    }

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------
    public function getSettingsId($module)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        return $processor->getSettingsId($module);
    }

    public function getDescendantModules($module)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        return $processor->getDescendantModules($module);
    }
}
