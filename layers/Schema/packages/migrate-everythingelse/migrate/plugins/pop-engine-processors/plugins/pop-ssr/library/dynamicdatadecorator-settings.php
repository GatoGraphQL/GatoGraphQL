<?php

/**
 * Settings Initialization:
 * We must associate the DynamicData Decorator Processor to each Processor/Wrapper
 */
global $pop_module_processordynamicdatadecorator_manager;
$pop_module_processordynamicdatadecorator_manager->add(PoPEngine_QueryDataModuleProcessorBase::class, PoP_DynamicDataModuleDecoratorProcessor::class);
