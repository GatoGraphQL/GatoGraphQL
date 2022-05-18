<?php

class PoP_Module_Processor_CustomMenuMultiples extends PoP_Module_Processor_MenuMultiplesBase
{
    public final const MODULE_MULTIPLE_MENU_SIDEBAR_ABOUT = 'multiple-menu-sidebar-about';
    public final const MODULE_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN = 'multiple-menu-top-userloggedin';
    public final const MODULE_MULTIPLE_MENU_TOPNAV_USERNOTLOGGEDIN = 'multiple-menu-top-usernotloggedin';
    public final const MODULE_MULTIPLE_MENU_TOPNAV_ABOUT = 'multiple-menu-top-about';
    public final const MODULE_MULTIPLE_MENU_TOP_ADDNEW = 'multiple-menu-top-addnew';
    public final const MODULE_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN = 'multiple-menu-home-usernotloggedin';
    public final const MODULE_MULTIPLE_MENU_SIDE_ADDNEW = 'multiple-menu-side-addnew';
    public final const MODULE_MULTIPLE_MENU_SIDE_SECTIONS = 'multiple-menu-side-sections';
    public final const MODULE_MULTIPLE_MENU_SIDE_SECTIONS_MULTITARGET = 'multiple-menu-side-sections-multitarget';
    public final const MODULE_MULTIPLE_MENU_SIDE_MYSECTIONS = 'multiple-menu-side-mysections';
    public final const MODULE_MULTIPLE_MENU_BODY_ADDCONTENT = 'multiple-menu-body-addcontent';
    public final const MODULE_MULTIPLE_MENU_BODY_SECTIONS = 'multiple-menu-body-sections';
    public final const MODULE_MULTIPLE_MENU_BODY_MYSECTIONS = 'multiple-menu-body-mysections';
    public final const MODULE_MULTIPLE_MENU_BODY_ABOUT = 'multiple-menu-body-about';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_MENU_SIDEBAR_ABOUT],
            [self::class, self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN],
            [self::class, self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERNOTLOGGEDIN],
            [self::class, self::COMPONENT_MULTIPLE_MENU_TOPNAV_ABOUT],
            [self::class, self::COMPONENT_MULTIPLE_MENU_TOP_ADDNEW],
            [self::class, self::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN],
            [self::class, self::COMPONENT_MULTIPLE_MENU_SIDE_ADDNEW],
            [self::class, self::COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS],
            [self::class, self::COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS_MULTITARGET],
            [self::class, self::COMPONENT_MULTIPLE_MENU_SIDE_MYSECTIONS],
            [self::class, self::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT],
            [self::class, self::COMPONENT_MULTIPLE_MENU_BODY_SECTIONS],
            [self::class, self::COMPONENT_MULTIPLE_MENU_BODY_MYSECTIONS],
            [self::class, self::COMPONENT_MULTIPLE_MENU_BODY_ABOUT],
        );
    }

    // function getRelevantRoute(array $component, array &$props) {

    //     $routes = array(
    //         self::COMPONENT_MULTIPLE_MENU_BODY_ABOUT => POP_COMMONPAGES_ROUTE_ABOUT,
    //         self::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT => POP_CONTENTCREATION_ROUTE_ADDCONTENT,
    //     );
    //     return $routes[$component[1]] ?? parent::getRelevantRoute($component, $props);
    // }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $inners = array(
            self::COMPONENT_MULTIPLE_MENU_SIDEBAR_ABOUT => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_SIDEBAR_ABOUT],
            self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_TOPNAV_USERLOGGEDIN],
            self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERNOTLOGGEDIN => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_TOPNAV_USERNOTLOGGEDIN],
            self::COMPONENT_MULTIPLE_MENU_TOPNAV_ABOUT => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_TOPNAV_ABOUT],
            self::COMPONENT_MULTIPLE_MENU_TOP_ADDNEW => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_TOP_ADDNEW],
            self::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_HOME_USERNOTLOGGEDIN],
            self::COMPONENT_MULTIPLE_MENU_SIDE_ADDNEW => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_SIDE_ADDNEW],
            self::COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_SIDE_SECTIONS],
            self::COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS_MULTITARGET => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_SIDE_SECTIONS_MULTITARGET],
            self::COMPONENT_MULTIPLE_MENU_SIDE_MYSECTIONS => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_SIDE_MYSECTIONS],
            self::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_BODY_ADDCONTENT],
            self::COMPONENT_MULTIPLE_MENU_BODY_SECTIONS => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_BODY_SECTIONS],
            self::COMPONENT_MULTIPLE_MENU_BODY_MYSECTIONS => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_BODY_MYSECTIONS],
            self::COMPONENT_MULTIPLE_MENU_BODY_ABOUT => [PoP_Module_Processor_CustomMenuDataloads::class, PoP_Module_Processor_CustomMenuDataloads::COMPONENT_DATALOAD_MENU_BODY_ABOUT],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        // Extra blocks
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN:
                $ret[] = [GD_UserPlatform_Module_Processor_AnchorControls::class, GD_UserPlatform_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_INVITENEWUSERS];
                break;
        }

        return $ret;
    }

    protected function getBlocksectionsClasses(array $component)
    {
        $ret = parent::getBlocksectionsClasses($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_MENU_SIDEBAR_ABOUT:
                $ret['controlgroup-top'] = 'top';
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'menu-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_MENU_TOP_ADDNEW:
            case self::COMPONENT_MULTIPLE_MENU_SIDE_ADDNEW:
                $this->appendProp($component, $props, 'class', 'addnew-menu');
                break;

            case self::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN:
                $this->appendProp($component, $props, 'class', 'multiple-menu-home-usernotloggedin');
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN:
            case self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERNOTLOGGEDIN:
            case self::COMPONENT_MULTIPLE_MENU_TOPNAV_ABOUT:
            case self::COMPONENT_MULTIPLE_MENU_SIDEBAR_ABOUT:
            case self::COMPONENT_MULTIPLE_MENU_TOP_ADDNEW:
            case self::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN:
                $this->appendProp([PoP_Module_Processor_IndentMenuLayouts::class, PoP_Module_Processor_IndentMenuLayouts::COMPONENT_LAYOUT_MENU_INDENT], $props, 'class', 'nav nav-condensed');
                break;

            case self::COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS:
            case self::COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS_MULTITARGET:
            case self::COMPONENT_MULTIPLE_MENU_SIDE_MYSECTIONS:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($component, $props, 'resourceloader', 'side-sections-menu');
                $this->appendProp($component, $props, 'class', 'side-sections-menu');
                break;

            case self::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT:
            case self::COMPONENT_MULTIPLE_MENU_BODY_SECTIONS:
            case self::COMPONENT_MULTIPLE_MENU_BODY_MYSECTIONS:
            case self::COMPONENT_MULTIPLE_MENU_BODY_ABOUT:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($component, $props, 'resourceloader', 'side-sections-menu');
                $this->appendProp($component, $props, 'class', 'side-sections-menu');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



