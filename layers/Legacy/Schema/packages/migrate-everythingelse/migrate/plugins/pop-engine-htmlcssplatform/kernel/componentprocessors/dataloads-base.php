<?php
use PoP\ComponentModel\ComponentProcessors\DataloadComponentProcessorTrait;

abstract class PoPHTMLCSSPlatform_Processor_DataloadsBase extends PoP_HTMLCSSPlatformQueryDataComponentProcessorBase
{
    use DataloadComponentProcessorTrait, PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait {

        PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait::initModelProps insteadof DataloadComponentProcessorTrait;
    }
}
