<?php

abstract class PoP_Module_Processor_FeedbackMessageInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_FEEDBACKMESSAGE_INNER];
    }
}
