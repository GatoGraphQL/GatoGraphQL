<?php
use PoP\ComponentModel\ModuleProcessors\DataloadingModuleInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadModuleProcessorTrait;

abstract class PoP_Engine_Module_Processor_DataloadsBase extends PoPEngine_QueryDataModuleProcessorBase implements DataloadingModuleInterface
{
    use DataloadModuleProcessorTrait, PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait {

        PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait::initModelProps insteadof DataloadModuleProcessorTrait;
    }
}
