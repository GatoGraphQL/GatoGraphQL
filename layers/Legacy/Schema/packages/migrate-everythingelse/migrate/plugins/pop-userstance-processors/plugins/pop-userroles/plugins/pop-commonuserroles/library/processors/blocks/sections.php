<?php

use PoP\ComponentModel\State\ApplicationState;

class UserStance_URE_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW = 'block-stances-byorganizations-scroll-fullview';
    public final const MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW = 'block-stances-byindividuals-scroll-fullview';
    public final const MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL = 'block-stances-byorganizations-scroll-thumbnail';
    public final const MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL = 'block-stances-byindividuals-scroll-thumbnail';
    public final const MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_LIST = 'block-stances-byorganizations-scroll-list';
    public final const MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_LIST = 'block-stances-byindividuals-scroll-list';
    public final const MODULE_BLOCK_STANCES_BYORGANIZATIONS_CAROUSEL = 'block-stances-byorganizations-carousel';
    public final const MODULE_BLOCK_STANCES_BYINDIVIDUALS_CAROUSEL = 'block-stances-byindividuals-carousel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_CAROUSEL],
            [self::class, self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_CAROUSEL => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_LIST => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_LIST => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_LIST => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
            self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_CAROUSEL => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_CAROUSEL],
            self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_CAROUSEL => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_CAROUSEL],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_SCROLL_LIST:
            case self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_SCROLL_LIST:
                // For the quickview we return something different
                if (\PoP\Root\App::getState('target') == POP_TARGET_QUICKVIEW) {
                    return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST];
                }

                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];

            case self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_CAROUSEL:
            case self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_CAROUSEL:
                return [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_STANCES_BYORGANIZATIONS_CAROUSEL:
            case self::MODULE_BLOCK_STANCES_BYINDIVIDUALS_CAROUSEL:
                // Artificial property added to identify the template when adding module-resources
                $this->appendProp($component, $props, 'class', 'pop-block-carousel block-stances-carousel');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



