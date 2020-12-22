<?php

abstract class PoP_Module_Processor_SriptCodesBase extends PoP_Module_Processor_CodesBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SCRIPTCODE];
    }
}
