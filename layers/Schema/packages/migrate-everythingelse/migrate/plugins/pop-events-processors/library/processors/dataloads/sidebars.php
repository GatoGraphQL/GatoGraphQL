<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;

class PoP_Events_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR = 'dataload-single-event-sidebar';
    public const MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR = 'dataload-single-pastevent-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR],
            [self::class, self::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $orientation = HooksAPIFacade::getInstance()->applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');
        $inners = array(
            self::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR => $vertical ?
                [GD_EM_Module_Processor_CustomVerticalSingleSidebars::class, GD_EM_Module_Processor_CustomVerticalSingleSidebars::MODULE_VERTICALSIDEBAR_SINGLE_EVENT] :
                [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT],
            self::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR => $vertical ?
                [GD_EM_Module_Processor_CustomVerticalSingleSidebars::class, GD_EM_Module_Processor_CustomVerticalSingleSidebars::MODULE_VERTICALSIDEBAR_SINGLE_PASTEVENT] :
                [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT],
        );

        if ($inner = $inners[$module[1]]) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR:
            case self::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR:
    //         case self::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR:
    //             return CustomPostRouteNatures::CUSTOMPOST;
    //     }

    //     return parent::getNature($module);
    // }


    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR:
            case self::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR:
                return EventTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR:
                $daterange_class = 'daterange-past opens-left';
                break;
        }
        if ($daterange_class) {
            $this->setProp([PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE], $props, 'daterange-class', $daterange_class);
        }

        parent::initModelProps($module, $props);
    }
}



