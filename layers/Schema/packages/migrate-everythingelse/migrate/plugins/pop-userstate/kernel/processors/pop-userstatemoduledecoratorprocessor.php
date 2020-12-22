<?php
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleDecoratorProcessor;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_UserStateModuleDecoratorProcessor extends AbstractModuleDecoratorProcessor
{

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getModuledecoratorprocessorManager()
    {
        return PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance();
    }

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function requiresUserState(array $module, array &$props)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);

        // Dataloading modules need to check for user state
        if ($processor->moduleLoadsData($module)) {
            // Check if the corresponding page requires state or not
            /*if ($checkpoint_configuration = $processor->getDataaccessCheckpointConfiguration($module, $props)) {

            return PoP_UserState_Utils::checkpointConfigurationRequiresUserState($checkpoint_configuration);
            }
            // Check if the corresponding page requires state or not
            else*/if ($route = $processor->getRelevantRoute($module, $props)) {
                return PoP_UserState_Utils::routeRequiresUserState($route);
}
        }

        return false;
    }
}

/**
 * Settings Initialization
 */
PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->add(AbstractModuleProcessor::class, PoP_UserStateModuleDecoratorProcessor::class);
