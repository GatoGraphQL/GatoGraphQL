<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

// Override ModuleProcessorClass
if (!\PoP\ComponentModel\Environment::disableCustomCMSCode()) {
    $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
    $moduleprocessor_manager->overrideProcessorClass(
        UserStance_Module_Processor_CustomSectionDataloads::class,
        UserStance_WP_Module_Processor_CustomSectionDataloads::class,
        [
            UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL,
        ]
    );
}
