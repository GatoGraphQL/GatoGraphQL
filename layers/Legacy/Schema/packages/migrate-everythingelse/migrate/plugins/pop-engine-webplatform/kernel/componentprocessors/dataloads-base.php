<?php
use PoP\ComponentModel\ComponentProcessors\DataloadComponentProcessorTrait;

abstract class PoPWebPlatform_Processor_DataloadsBase extends PoP_WebPlatformQueryDataComponentProcessorBase
{
    use DataloadComponentProcessorTrait, PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait {

        PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait::initModelProps insteadof DataloadComponentProcessorTrait;
    }
}
