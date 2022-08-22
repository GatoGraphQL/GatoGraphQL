<?php

class PoP_Module_Processor_CustomMenuMultiples extends PoP_Module_Processor_MenuMultiplesBase
{
    public final const COMPONENT_MULTIPLE_MENU_SIDEBAR_ABOUT = 'multiple-menu-sidebar-about';
    public final const COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN = 'multiple-menu-top-userloggedin';
    public final const COMPONENT_MULTIPLE_MENU_TOPNAV_USERNOTLOGGEDIN = 'multiple-menu-top-usernotloggedin';
    public final const COMPONENT_MULTIPLE_MENU_TOPNAV_ABOUT = 'multiple-menu-top-about';
    public final const COMPONENT_MULTIPLE_MENU_TOP_ADDNEW = 'multiple-menu-top-addnew';
    public final const COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN = 'multiple-menu-home-usernotloggedin';
    public final const COMPONENT_MULTIPLE_MENU_SIDE_ADDNEW = 'multiple-menu-side-addnew';
    public final const COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS = 'multiple-menu-side-sections';
    public final const COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS_MULTITARGET = 'multiple-menu-side-sections-multitarget';
    public final const COMPONENT_MULTIPLE_MENU_SIDE_MYSECTIONS = 'multiple-menu-side-mysections';
    public final const COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT = 'multiple-menu-body-addcontent';
    public final const COMPONENT_MULTIPLE_MENU_BODY_SECTIONS = 'multiple-menu-body-sections';
    public final const COMPONENT_MULTIPLE_MENU_BODY_MYSECTIONS = 'multiple-menu-body-mysections';
    public final const COMPONENT_MULTIPLE_MENU_BODY_ABOUT = 'multiple-menu-body-about';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_MENU_SIDEBAR_ABOUT,
            self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN,
            self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERNOTLOGGEDIN,
            self::COMPONENT_MULTIPLE_MENU_TOPNAV_ABOUT,
            self::COMPONENT_MULTIPLE_MENU_TOP_ADDNEW,
            self::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN,
            self::COMPONENT_MULTIPLE_MENU_SIDE_ADDNEW,
            self::COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS,
            self::COMPONENT_MULTIPLE_MENU_SIDE_SECTIONS_MULTITARGET,
            self::COMPONENT_MULTIPLE_MENU_SIDE_MYSECTIONS,
            self::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT,
            self::COMPONENT_MULTIPLE_MENU_BODY_SECTIONS,
            self::COMPONENT_MULTIPLE_MENU_BODY_MYSECTIONS,
            self::COMPONENT_MULTIPLE_MENU_BODY_ABOUT,
        );
    }

    // function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     $routes = array(
    //         self::COMPONENT_MULTIPLE_MENU_BODY_ABOUT => POP_COMMONPAGES_ROUTE_ABOUT,
    //         self::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT => POP_CONTENTCREATION_ROUTE_ADDCONTENT,
    //     );
    //     return $routes[$component->name] ?? parent::getRelevantRoute($component, $props);
    // }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

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
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        // Extra blocks
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN:
                $ret[] = [GD_UserPlatform_Module_Processor_AnchorControls::class, GD_UserPlatform_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_INVITENEWUSERS];
                break;
        }

        return $ret;
    }

    protected function getBlocksectionsClasses(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBlocksectionsClasses($component);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_MENU_SIDEBAR_ABOUT:
                $ret['controlgroup-top'] = 'top';
                break;
        }

        return $ret;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'menu-';
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_MENU_TOP_ADDNEW:
            case self::COMPONENT_MULTIPLE_MENU_SIDE_ADDNEW:
                $this->appendProp($component, $props, 'class', 'addnew-menu');
                break;

            case self::COMPONENT_MULTIPLE_MENU_HOME_USERNOTLOGGEDIN:
                $this->appendProp($component, $props, 'class', 'multiple-menu-home-usernotloggedin');
                break;
        }

        switch ($component->name) {
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
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'side-sections-menu');
                $this->appendProp($component, $props, 'class', 'side-sections-menu');
                break;

            case self::COMPONENT_MULTIPLE_MENU_BODY_ADDCONTENT:
            case self::COMPONENT_MULTIPLE_MENU_BODY_SECTIONS:
            case self::COMPONENT_MULTIPLE_MENU_BODY_MYSECTIONS:
            case self::COMPONENT_MULTIPLE_MENU_BODY_ABOUT:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'side-sections-menu');
                $this->appendProp($component, $props, 'class', 'side-sections-menu');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



