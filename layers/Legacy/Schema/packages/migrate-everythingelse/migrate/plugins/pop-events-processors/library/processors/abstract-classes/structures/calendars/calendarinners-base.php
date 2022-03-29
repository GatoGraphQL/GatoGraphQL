<?php

abstract class PoP_Module_Processor_CalendarInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_CALENDAR_INNER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        $ret = array_merge(
            $ret,
            array('author', 'title', 'startDate', 'endDate', 'isAllDay')
        );

        return $ret;
    }
}
