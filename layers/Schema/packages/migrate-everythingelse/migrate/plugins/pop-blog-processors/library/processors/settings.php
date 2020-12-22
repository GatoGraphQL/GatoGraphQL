<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Posts_Module_Processor_FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Users_Module_Processor_FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PoP_Users_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Users_Posts_Module_Processor_FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PoP_Users_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Tags_Module_Processor_FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PoP_Tags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
        PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
    ]
);


