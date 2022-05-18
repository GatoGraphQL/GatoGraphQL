<?php

class GD_URE_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR = 'block-organizations-scroll-navigator';
    public final const MODULE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR = 'block-individuals-scroll-navigator';
    public final const MODULE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS = 'block-organizations-scroll-addons';
    public final const MODULE_BLOCK_INDIVIDUALS_SCROLL_ADDONS = 'block-individuals-scroll-addons';
    public final const MODULE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS = 'block-organizations-scroll-details';
    public final const MODULE_BLOCK_INDIVIDUALS_SCROLL_DETAILS = 'block-individuals-scroll-details';
    public final const MODULE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW = 'block-organizations-scroll-fullview';
    public final const MODULE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW = 'block-individuals-scroll-fullview';
    public final const MODULE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL = 'block-organizations-scroll-thumbnail';
    public final const MODULE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL = 'block-individuals-scroll-thumbnail';
    public final const MODULE_BLOCK_ORGANIZATIONS_SCROLL_LIST = 'block-organizations-scroll-list';
    public final const MODULE_BLOCK_INDIVIDUALS_SCROLL_LIST = 'block-individuals-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_INDIVIDUALS_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_INDIVIDUALS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_INDIVIDUALS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_ADDONS => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_DETAILS => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_LIST => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS ,
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_LIST => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS ,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_ADDONS],
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_ADDONS => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_ADDONS],
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_DETAILS => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_LIST => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
            self::MODULE_BLOCK_INDIVIDUALS_SCROLL_LIST => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_INDIVIDUALS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_ORGANIZATIONS_SCROLL_LIST:
            case self::MODULE_BLOCK_INDIVIDUALS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKUSERLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }
}



