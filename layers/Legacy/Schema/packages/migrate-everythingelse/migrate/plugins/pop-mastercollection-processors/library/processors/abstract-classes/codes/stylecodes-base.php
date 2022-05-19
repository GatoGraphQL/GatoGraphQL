<?php

abstract class PoP_Module_Processor_StyleCodesBase extends PoP_Module_Processor_CodesBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_STYLECODE];
    }
}
