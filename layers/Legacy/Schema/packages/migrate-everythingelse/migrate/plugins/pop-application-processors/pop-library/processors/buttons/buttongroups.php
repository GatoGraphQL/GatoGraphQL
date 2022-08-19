<?php

class GD_Custom_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const COMPONENT_BUTTONGROUP_SECTION = 'buttongroup-section';
    public final const COMPONENT_BUTTONGROUP_SECTIONWITHMAP = 'buttongroup-sectionwithmap';
    public final const COMPONENT_BUTTONGROUP_TAGSECTION = 'buttongroup-tagsection';
    public final const COMPONENT_BUTTONGROUP_TAGSECTIONWITHMAP = 'buttongroup-tagsectionwithmap';
    public final const COMPONENT_BUTTONGROUP_USERS = 'buttongroup-users';
    public final const COMPONENT_BUTTONGROUP_MYCONTENT = 'buttongroup-mycontent';
    public final const COMPONENT_BUTTONGROUP_HOMESECTION = 'buttongroup-homesection';
    public final const COMPONENT_BUTTONGROUP_AUTHORSECTION = 'buttongroup-authorsection';
    public final const COMPONENT_BUTTONGROUP_AUTHORSECTIONWITHMAP = 'buttongroup-authorsectionwithmap';
    public final const COMPONENT_BUTTONGROUP_AUTHORUSERS = 'buttongroup-authorusers';
    public final const COMPONENT_BUTTONGROUP_TAGS = 'buttongroup-tags';
    public final const COMPONENT_BUTTONGROUP_AUTHORTAGS = 'buttongroup-authortags';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONGROUP_SECTION,
            self::COMPONENT_BUTTONGROUP_SECTIONWITHMAP,
            self::COMPONENT_BUTTONGROUP_TAGSECTION,
            self::COMPONENT_BUTTONGROUP_TAGSECTIONWITHMAP,
            self::COMPONENT_BUTTONGROUP_USERS,
            self::COMPONENT_BUTTONGROUP_MYCONTENT,
            self::COMPONENT_BUTTONGROUP_HOMESECTION,
            self::COMPONENT_BUTTONGROUP_AUTHORSECTION,
            self::COMPONENT_BUTTONGROUP_AUTHORSECTIONWITHMAP,
            self::COMPONENT_BUTTONGROUP_AUTHORUSERS,
            self::COMPONENT_BUTTONGROUP_TAGS,
            self::COMPONENT_BUTTONGROUP_AUTHORTAGS,
        );
    }

    protected function getHeadersdataScreen(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $screens = array(
            self::COMPONENT_BUTTONGROUP_SECTION => POP_SCREEN_SECTION,
            self::COMPONENT_BUTTONGROUP_SECTIONWITHMAP => POP_SCREEN_SECTION,
            self::COMPONENT_BUTTONGROUP_TAGSECTION => POP_SCREEN_TAGSECTION,
            self::COMPONENT_BUTTONGROUP_TAGSECTIONWITHMAP => POP_SCREEN_TAGSECTION,
            self::COMPONENT_BUTTONGROUP_USERS => POP_SCREEN_USERS,
            self::COMPONENT_BUTTONGROUP_MYCONTENT => POP_SCREEN_MYCONTENT,
            self::COMPONENT_BUTTONGROUP_HOMESECTION => POP_SCREEN_HOMESECTION,
            self::COMPONENT_BUTTONGROUP_AUTHORSECTION => POP_SCREEN_AUTHORSECTION,
            self::COMPONENT_BUTTONGROUP_AUTHORSECTIONWITHMAP => POP_SCREEN_AUTHORSECTION,
            self::COMPONENT_BUTTONGROUP_AUTHORUSERS => POP_SCREEN_AUTHORUSERS,
            self::COMPONENT_BUTTONGROUP_TAGS => POP_SCREEN_TAGS,
            self::COMPONENT_BUTTONGROUP_AUTHORTAGS => POP_SCREEN_AUTHORTAGS,
        );
        if ($screen = $screens[$component->name] ?? null) {
            return $screen;
        }

        return parent::getHeadersdataScreen($component, $props);
    }

    protected function getHeadersdataformatsHasmap(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONGROUP_USERS:
            case self::COMPONENT_BUTTONGROUP_SECTIONWITHMAP:
            case self::COMPONENT_BUTTONGROUP_TAGSECTIONWITHMAP:
            case self::COMPONENT_BUTTONGROUP_AUTHORSECTIONWITHMAP:
            case self::COMPONENT_BUTTONGROUP_AUTHORUSERS:
                return true;
        }

        return parent::getHeadersdataformatsHasmap($component, $props);
    }
}



