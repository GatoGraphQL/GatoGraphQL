<?php

/**
 * Settings Initialization:
 * We must associate the Resource Decorator Processor to each Processor/Wrapper
 */
global $pop_resourcemoduledecoratorprocessor_manager;
$pop_resourcemoduledecoratorprocessor_manager->add(PoPEngine_QueryDataComponentProcessorBase::class, PoP_ResourceModuleDecoratorProcessor::class);
