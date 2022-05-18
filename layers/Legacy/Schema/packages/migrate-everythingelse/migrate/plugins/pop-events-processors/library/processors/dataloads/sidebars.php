<?php
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;

class PoP_Events_Module_Processor_CustomSidebarDataloads extends PoP_Module_Processor_DataloadsBase
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_DATALOAD_SINGLE_EVENT_SIDEBAR = 'dataload-single-event-sidebar';
    public final const COMPONENT_DATALOAD_SINGLE_PASTEVENT_SIDEBAR = 'dataload-single-pastevent-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_SINGLE_EVENT_SIDEBAR],
            [self::class, self::COMPONENT_DATALOAD_SINGLE_PASTEVENT_SIDEBAR],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $orientation = \PoP\Root\App::applyFilters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
        $vertical = ($orientation == 'vertical');
        $inners = array(
            self::COMPONENT_DATALOAD_SINGLE_EVENT_SIDEBAR => $vertical ?
                [GD_EM_Module_Processor_CustomVerticalSingleSidebars::class, GD_EM_Module_Processor_CustomVerticalSingleSidebars::COMPONENT_VERTICALSIDEBAR_SINGLE_EVENT] :
                [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT],
            self::COMPONENT_DATALOAD_SINGLE_PASTEVENT_SIDEBAR => $vertical ?
                [GD_EM_Module_Processor_CustomVerticalSingleSidebars::class, GD_EM_Module_Processor_CustomVerticalSingleSidebars::COMPONENT_VERTICALSIDEBAR_SINGLE_PASTEVENT] :
                [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLE_EVENT_SIDEBAR:
            case self::COMPONENT_DATALOAD_SINGLE_PASTEVENT_SIDEBAR:
                return $this->getQueriedDBObjectID($component, $props, $data_properties);
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_DATALOAD_SINGLE_EVENT_SIDEBAR:
    //         case self::COMPONENT_DATALOAD_SINGLE_PASTEVENT_SIDEBAR:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }


    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLE_EVENT_SIDEBAR:
            case self::COMPONENT_DATALOAD_SINGLE_PASTEVENT_SIDEBAR:
                return $this->instanceManager->getInstance(EventObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_SINGLE_PASTEVENT_SIDEBAR:
                $daterange_class = 'daterange-past opens-left';
                break;
        }
        if ($daterange_class) {
            $this->setProp([PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_EVENTSCOPE], $props, 'daterange-class', $daterange_class);
        }

        parent::initModelProps($component, $props);
    }
}



