<?php
use PoP\ComponentModel\ModuleProcessors\DataloadModuleProcessorTrait;

abstract class PoPWebPlatform_Processor_DataloadsBase extends PoP_WebPlatformQueryDataModuleProcessorBase
{
    use DataloadModuleProcessorTrait, PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait {

        PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait::initModelProps insteadof DataloadModuleProcessorTrait;
    }
}
