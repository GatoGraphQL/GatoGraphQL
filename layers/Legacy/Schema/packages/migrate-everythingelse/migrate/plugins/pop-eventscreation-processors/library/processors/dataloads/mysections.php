<?php
use PoPCMSSchema\Events\ModuleProcessors\PastEventModuleProcessorTrait;
use PoPCMSSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;

class PoP_EventsCreation_Module_Processor_MySectionDataloads extends PoP_EventsCreation_Module_Processor_MySectionDataloadsBase
{
    use PastEventModuleProcessorTrait;

    public const MODULE_DATALOAD_MYEVENTS_TABLE_EDIT = 'dataload-myevents-table-edit';
    public const MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT = 'dataload-mypastevents-table-edit';
    public const MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-myevents-scroll-simpleviewpreview';
    public const MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW = 'dataload-mypastevents-scroll-simpleviewpreview';
    public const MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW = 'dataload-myevents-scroll-fullviewpreview';
    public const MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW = 'dataload-mypastevents-scroll-fullviewpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW => POP_EVENTSCREATION_ROUTE_MYEVENTS,
            self::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW => POP_EVENTSCREATION_ROUTE_MYEVENTS,
            self::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT => POP_EVENTSCREATION_ROUTE_MYEVENTS,
            self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
            self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
            self::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

            /*********************************************
             * My Content Tables
             *********************************************/
            self::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT => [GD_EM_Module_Processor_Tables::class, GD_EM_Module_Processor_Tables::MODULE_TABLE_MYEVENTS],
            self::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT => [GD_EM_Module_Processor_Tables::class, GD_EM_Module_Processor_Tables::MODULE_TABLE_MYPASTEVENTS],

            /*********************************************
             * My Content Full Post Previews
             *********************************************/
            self::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrolls::class, PoP_EventsCreation_Module_Processor_CustomScrolls::MODULE_SCROLL_MYEVENTS_SIMPLEVIEWPREVIEW],
            self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrolls::class, PoP_EventsCreation_Module_Processor_CustomScrolls::MODULE_SCROLL_MYPASTEVENTS_SIMPLEVIEWPREVIEW],

            self::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrolls::class, PoP_EventsCreation_Module_Processor_CustomScrolls::MODULE_SCROLL_MYEVENTS_FULLVIEWPREVIEW],
            self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_CustomScrolls::class, PoP_EventsCreation_Module_Processor_CustomScrolls::MODULE_SCROLL_MYPASTEVENTS_FULLVIEWPREVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_EventsCreation_Module_Processor_CustomFilters::class, PoP_EventsCreation_Module_Processor_CustomFilters::MODULE_FILTER_MYEVENTS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $tables = array(
            [self::class, self::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
        );
        if (in_array($module, $tables)) {
            $format = POP_FORMAT_TABLE;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        }

        return $format ?? parent::getFormat($module);
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW:
                $this->addPastEventImmutableDataloadQueryArgs($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW:
                return $this->instanceManager->getInstance(EventObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Events: choose to only select past/future
        $past = array(
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
        );
        $future = array(
            [self::class, self::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
        );
        if (in_array($module, $past)) {
            $daterange_class = 'daterange-past opens-right';
        } elseif (in_array($module, $future)) {
            $daterange_class = 'daterange-future opens-right';
        }
        if ($daterange_class) {
            $this->setProp([PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE], $props, 'daterange-class', $daterange_class);
        }

        parent::initModelProps($module, $props);
    }
}



