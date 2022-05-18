<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

// Override ComponentProcessorClass
if (!\PoP\Application\Environment::disableCustomCMSCode()) {
    $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    $moduleprocessor_manager->overrideProcessorClass(
        UserStance_Module_Processor_CustomSectionDataloads::class,
        UserStance_WP_Module_Processor_CustomSectionDataloads::class,
        [
            UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_CAROUSEL,
        ]
    );
}
