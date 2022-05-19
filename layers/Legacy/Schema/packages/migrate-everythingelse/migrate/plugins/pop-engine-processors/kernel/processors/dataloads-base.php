<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingModuleInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadComponentProcessorTrait;

abstract class PoP_Engine_Module_Processor_DataloadsBase extends PoPEngine_QueryDataComponentProcessorBase implements DataloadingModuleInterface
{
    use DataloadComponentProcessorTrait, PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait {

        PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait::initModelProps insteadof DataloadComponentProcessorTrait;
    }
}
