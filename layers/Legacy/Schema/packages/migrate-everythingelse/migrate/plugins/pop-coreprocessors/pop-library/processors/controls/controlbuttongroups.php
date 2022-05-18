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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_FILTER],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_CURRENTURL],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_LOADLATESTBLOCK],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_SUBMENU_XS],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_RESETEDITOR],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_SHARE],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_ADDCOMMENT],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_ALLTAGSLINK],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS:
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEOPTIONALFIELDS];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_FILTER:
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_FILTERTOGGLE];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_CURRENTURL:
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_CURRENTURL];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP:
                $ret[] = [PoP_Module_Processor_ButtonControls::class, PoP_Module_Processor_ButtonControls::COMPONENT_BUTTONCONTROL_RELOADBLOCKGROUP];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK:
                $ret[] = [PoP_Module_Processor_ButtonControls::class, PoP_Module_Processor_ButtonControls::COMPONENT_BUTTONCONTROL_RELOADBLOCK];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_LOADLATESTBLOCK:
                $ret[] = [PoP_Module_Processor_ButtonControls::class, PoP_Module_Processor_ButtonControls::COMPONENT_BUTTONCONTROL_LOADLATESTBLOCK];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_SUBMENU_XS:
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_SUBMENUTOGGLE_XS];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_RESETEDITOR:
                $ret[] = [PoP_Module_Processor_ButtonControls::class, PoP_Module_Processor_ButtonControls::COMPONENT_BUTTONCONTROL_RESETEDITOR];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_SHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonControls::class, PoP_Module_Processor_DropdownButtonControls::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE:
                $ret[] = [PoP_Module_Processor_DropdownButtonControls::class, PoP_Module_Processor_DropdownButtonControls::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
                break;

            case self::COMPONENT_CONTROLBUTTONGROUP_ALLTAGSLINK:
                $ret[] = [PoP_Module_Processor_CustomAnchorControls::class, PoP_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TAGSLINK];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTROLBUTTONGROUP_SUBMENU_XS:
                $this->appendProp($component, $props, 'class', 'hidden-sm hidden-md hidden-lg');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


