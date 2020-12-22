<?php

class GD_Custom_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public const MODULE_BUTTONGROUP_SECTION = 'buttongroup-section';
    public const MODULE_BUTTONGROUP_SECTIONWITHMAP = 'buttongroup-sectionwithmap';
    public const MODULE_BUTTONGROUP_TAGSECTION = 'buttongroup-tagsection';
    public const MODULE_BUTTONGROUP_TAGSECTIONWITHMAP = 'buttongroup-tagsectionwithmap';
    public const MODULE_BUTTONGROUP_USERS = 'buttongroup-users';
    public const MODULE_BUTTONGROUP_MYCONTENT = 'buttongroup-mycontent';
    public const MODULE_BUTTONGROUP_HOMESECTION = 'buttongroup-homesection';
    public const MODULE_BUTTONGROUP_AUTHORSECTION = 'buttongroup-authorsection';
    public const MODULE_BUTTONGROUP_AUTHORSECTIONWITHMAP = 'buttongroup-authorsectionwithmap';
    public const MODULE_BUTTONGROUP_AUTHORUSERS = 'buttongroup-authorusers';
    public const MODULE_BUTTONGROUP_TAGS = 'buttongroup-tags';
    public const MODULE_BUTTONGROUP_AUTHORTAGS = 'buttongroup-authortags';

    public function getModulesToProcess(): array
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

    protected function getHeadersdataScreen(array $module, array &$props)
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
        if ($screen = $screens[$module[1]]) {
            return $screen;
        }

        return parent::getHeadersdataScreen($module, $props);
    }

    protected function getHeadersdataformatsHasmap(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONGROUP_USERS:
            case self::MODULE_BUTTONGROUP_SECTIONWITHMAP:
            case self::MODULE_BUTTONGROUP_TAGSECTIONWITHMAP:
            case self::MODULE_BUTTONGROUP_AUTHORSECTIONWITHMAP:
            case self::MODULE_BUTTONGROUP_AUTHORUSERS:
                return true;
        }

        return parent::getHeadersdataformatsHasmap($module, $props);
    }
}



