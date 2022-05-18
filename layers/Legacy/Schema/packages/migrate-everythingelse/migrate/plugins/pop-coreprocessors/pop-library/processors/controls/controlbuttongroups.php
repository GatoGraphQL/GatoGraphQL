<?php

class PoP_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS = 'controlbuttongroup-toggleoptionalfields';
    public final const MODULE_CONTROLBUTTONGROUP_FILTER = 'controlbuttongroup-filter';
    public final const MODULE_CONTROLBUTTONGROUP_CURRENTURL = 'controlbuttongroup-currenturl';
    public final const MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP = 'controlbuttongroup-reloadblockgroup';
    public final const MODULE_CONTROLBUTTONGROUP_RELOADBLOCK = 'controlbuttongroup-reloadblock';
    public final const MODULE_CONTROLBUTTONGROUP_LOADLATESTBLOCK = 'controlbuttongroup-loadlatestblock';
    public final const MODULE_CONTROLBUTTONGROUP_SUBMENU_XS = 'controlbuttongroup-submenu-xs';
    public final const MODULE_CONTROLBUTTONGROUP_RESETEDITOR = 'controlbuttongroup-reseteditor';
    public final const MODULE_CONTROLBUTTONGROUP_SHARE = 'controlbuttongroup-share';
    public final const MODULE_CONTROLBUTTONGROUP_RESULTSSHARE = 'controlbuttongroup-resultsshare';
    public final const MODULE_CONTROLBUTTONGROUP_ADDCOMMENT = 'controlbuttongroup-addcomment';
    public final const MODULE_CONTROLBUTTONGROUP_ALLTAGSLINK = 'controlbuttongroup-alltagslink';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_FILTER],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_CURRENTURL],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_LOADLATESTBLOCK],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_SUBMENU_XS],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_RESETEDITOR],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_SHARE],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_ADDCOMMENT],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_ALLTAGSLINK],
        );
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        switch ($module[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS:
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_FILTER:
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_FILTERTOGGLE];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_CURRENTURL:
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_CURRENTURL];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP:
                $ret[] = [PoP_Module_Processor_ButtonControls::class, PoP_Module_Processor_ButtonControls::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK:
                $ret[] = [PoP_Module_Processor_ButtonControls::class, PoP_Module_Processor_ButtonControls::MODULE_BUTTONCONTROL_RELOADBLOCK];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_LOADLATESTBLOCK:
                $ret[] = [PoP_Module_Processor_ButtonControls::class, PoP_Module_Processor_ButtonControls::MODULE_BUTTONCONTROL_LOADLATESTBLOCK];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_SUBMENU_XS:
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_RESETEDITOR:
                $ret[] = [PoP_Module_Processor_ButtonControls::class, PoP_Module_Processor_ButtonControls::MODULE_BUTTONCONTROL_RESETEDITOR];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_SHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonControls::class, PoP_Module_Processor_DropdownButtonControls::MODULE_DROPDOWNBUTTONCONTROL_SHARE];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonControls::class, PoP_Module_Processor_DropdownButtonControls::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
                break;

            case self::MODULE_CONTROLBUTTONGROUP_ALLTAGSLINK:
                $ret[] = [PoP_Module_Processor_CustomAnchorControls::class, PoP_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_TAGSLINK];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_SUBMENU_XS:
                $this->appendProp($module, $props, 'class', 'hidden-sm hidden-md hidden-lg');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


