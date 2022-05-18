<?php

class GD_Custom_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const MODULE_BUTTONGROUP_SECTION = 'buttongroup-section';
    public final const MODULE_BUTTONGROUP_SECTIONWITHMAP = 'buttongroup-sectionwithmap';
    public final const MODULE_BUTTONGROUP_TAGSECTION = 'buttongroup-tagsection';
    public final const MODULE_BUTTONGROUP_TAGSECTIONWITHMAP = 'buttongroup-tagsectionwithmap';
    public final const MODULE_BUTTONGROUP_USERS = 'buttongroup-users';
    public final const MODULE_BUTTONGROUP_MYCONTENT = 'buttongroup-mycontent';
    public final const MODULE_BUTTONGROUP_HOMESECTION = 'buttongroup-homesection';
    public final const MODULE_BUTTONGROUP_AUTHORSECTION = 'buttongroup-authorsection';
    public final const MODULE_BUTTONGROUP_AUTHORSECTIONWITHMAP = 'buttongroup-authorsectionwithmap';
    public final const MODULE_BUTTONGROUP_AUTHORUSERS = 'buttongroup-authorusers';
    public final const MODULE_BUTTONGROUP_TAGS = 'buttongroup-tags';
    public final const MODULE_BUTTONGROUP_AUTHORTAGS = 'buttongroup-authortags';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONGROUP_SECTION],
            [self::class, self::MODULE_BUTTONGROUP_SECTIONWITHMAP],
            [self::class, self::MODULE_BUTTONGROUP_TAGSECTION],
            [self::class, self::MODULE_BUTTONGROUP_TAGSECTIONWITHMAP],
            [self::class, self::MODULE_BUTTONGROUP_USERS],
            [self::class, self::MODULE_BUTTONGROUP_MYCONTENT],
            [self::class, self::MODULE_BUTTONGROUP_HOMESECTION],
            [self::class, self::MODULE_BUTTONGROUP_AUTHORSECTION],
            [self::class, self::MODULE_BUTTONGROUP_AUTHORSECTIONWITHMAP],
            [self::class, self::MODULE_BUTTONGROUP_AUTHORUSERS],
            [self::class, self::MODULE_BUTTONGROUP_TAGS],
            [self::class, self::MODULE_BUTTONGROUP_AUTHORTAGS],
        );
    }

    protected function getHeadersdataScreen(array $component, array &$props)
    {
        $screens = array(
            self::MODULE_BUTTONGROUP_SECTION => POP_SCREEN_SECTION,
            self::MODULE_BUTTONGROUP_SECTIONWITHMAP => POP_SCREEN_SECTION,
            self::MODULE_BUTTONGROUP_TAGSECTION => POP_SCREEN_TAGSECTION,
            self::MODULE_BUTTONGROUP_TAGSECTIONWITHMAP => POP_SCREEN_TAGSECTION,
            self::MODULE_BUTTONGROUP_USERS => POP_SCREEN_USERS,
            self::MODULE_BUTTONGROUP_MYCONTENT => POP_SCREEN_MYCONTENT,
            self::MODULE_BUTTONGROUP_HOMESECTION => POP_SCREEN_HOMESECTION,
            self::MODULE_BUTTONGROUP_AUTHORSECTION => POP_SCREEN_AUTHORSECTION,
            self::MODULE_BUTTONGROUP_AUTHORSECTIONWITHMAP => POP_SCREEN_AUTHORSECTION,
            self::MODULE_BUTTONGROUP_AUTHORUSERS => POP_SCREEN_AUTHORUSERS,
            self::MODULE_BUTTONGROUP_TAGS => POP_SCREEN_TAGS,
            self::MODULE_BUTTONGROUP_AUTHORTAGS => POP_SCREEN_AUTHORTAGS,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getHeadersdataScreen($component, $props);
    }

    protected function getHeadersdataformatsHasmap(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_BUTTONGROUP_USERS:
            case self::MODULE_BUTTONGROUP_SECTIONWITHMAP:
            case self::MODULE_BUTTONGROUP_TAGSECTIONWITHMAP:
            case self::MODULE_BUTTONGROUP_AUTHORSECTIONWITHMAP:
            case self::MODULE_BUTTONGROUP_AUTHORUSERS:
                return true;
        }

        return parent::getHeadersdataformatsHasmap($component, $props);
    }
}



