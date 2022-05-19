<?php

abstract class PoP_Module_Processor_TableInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_TABLE_INNER];
    }
}
