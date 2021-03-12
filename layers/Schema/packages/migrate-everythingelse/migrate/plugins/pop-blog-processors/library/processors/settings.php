<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSchema\PostTags\ModuleProcessors\TagPostFieldDataloads;
use PoPSchema\Tags\ModuleProcessors\FieldDataloads as TagFieldDataloads;
use PoPSchema\Posts\ModuleProcessors\FieldDataloads as PostFieldDataloads;
use PoPSchema\Users\ModuleProcessors\FieldDataloads as UserFieldDataloads;
use PoPSchema\Users\Conditional\CustomPosts\ModuleProcessors\FieldDataloads as UserCustomPostFieldDataloads;

$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
$moduleprocessor_manager->overrideProcessorClass(
    PostFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PostFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    UserFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        UserFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    UserCustomPostFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        UserCustomPostFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        TagFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
        TagPostFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
    ]
);


