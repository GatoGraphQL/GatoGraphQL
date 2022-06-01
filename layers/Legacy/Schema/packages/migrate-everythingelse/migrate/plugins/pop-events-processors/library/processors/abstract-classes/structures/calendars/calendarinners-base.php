<?php

abstract class PoP_Module_Processor_CalendarInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Events_TemplateResourceLoaderProcessor::class, PoP_Events_TemplateResourceLoaderProcessor::RESOURCE_CALENDAR_INNER];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);

        $ret = array_merge(
            $ret,
            array('author', 'title', 'startDate', 'endDate', 'isAllDay')
        );

        return $ret;
    }
}
