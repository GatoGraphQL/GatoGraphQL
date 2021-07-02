<?php
use PoP\ComponentModel\ModuleProcessors\DataloadModuleProcessorTrait;

abstract class PoPHTMLCSSPlatform_Processor_DataloadsBase extends PoP_HTMLCSSPlatformQueryDataModuleProcessorBase
{
    use DataloadModuleProcessorTrait, PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait {

        PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait::initModelProps insteadof DataloadModuleProcessorTrait;
    }
}
