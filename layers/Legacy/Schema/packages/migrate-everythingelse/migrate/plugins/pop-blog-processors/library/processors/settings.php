<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoPCMSSchema\Posts\ConditionalOnModule\Users\ComponentProcessors\FieldDataloadComponentProcessor as UserPostFieldDataloads;
use PoPCMSSchema\Posts\ComponentProcessors\FieldDataloadComponentProcessor as PostFieldDataloads;
use PoPCMSSchema\PostTags\ComponentProcessors\PostTagFieldDataloadComponentProcessor;
use PoPCMSSchema\PostTags\ComponentProcessors\TagPostFieldDataloadComponentProcessor;
use PoPCMSSchema\Users\ComponentProcessors\FieldDataloadComponentProcessor as UserFieldDataloads;

$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
/**
 * @todo Use the ServiceContainer to override this class! (method `overrideProcessorClass` has been removed)
 */
$componentprocessor_manager->overrideProcessorClass(
    PostFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PostFieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST,
    ]
);
/**
 * @todo Use the ServiceContainer to override this class! (method `overrideProcessorClass` has been removed)
 */
$componentprocessor_manager->overrideProcessorClass(
    UserFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        UserFieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST,
    ]
);
/**
 * @todo Use the ServiceContainer to override this class! (method `overrideProcessorClass` has been removed)
 */
$componentprocessor_manager->overrideProcessorClass(
    UserPostFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        UserPostFieldDataloads::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
    ]
);
/**
 * @todo Use the ServiceContainer to override this class! (method `overrideProcessorClass` has been removed)
 */
$componentprocessor_manager->overrideProcessorClass(
    FieldDataloadComponentProcessor::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PostTagFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST,
        TagPostFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
    ]
);
