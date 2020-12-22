<?php

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

// Override ModuleProcessorClass
$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Posts_Module_Processor_TextFilterInputs::class,
    PoP_Module_Processor_TextFilterInputs::class,
    [
        PoP_Posts_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH,
    ]
);
