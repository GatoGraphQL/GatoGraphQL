<?php
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

// Override ModuleProcessorClass
$instanceManager = InstanceManagerFacade::getInstance();
$instanceManager->overrideClass(
    PoP_Module_Processor_Entries::class,
    PoP_SPA_Module_Processor_Entries::class
);
