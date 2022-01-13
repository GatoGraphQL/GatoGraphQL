<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_SocialNetwork_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public const MODULE_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR = 'multiple-authorfollowers-sidebar';
    public const MODULE_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR = 'multiple-authorfollowingusers-sidebar';
    public const MODULE_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR = 'multiple-authorsubscribedtotags-sidebar';
    public const MODULE_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR = 'multiple-authorrecommendedposts-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::MODULE_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $filters = array(
                    self::MODULE_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                    self::MODULE_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                    self::MODULE_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_AUTHORTAGS_SIDEBAR],
                    self::MODULE_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                );
                if ($filter = $filters[$module[1]] ?? null) {
                    $ret[] = $filter;
                }

                // Allow URE to add the Organization/Individual sidebars below
                $ret = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_UserCommunities_Module_Processor_SidebarMultiples:sidebar-layouts',
                    $ret,
                    $author,
                    $module
                );
                break;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR => POP_SCREEN_AUTHORUSERS,
            self::MODULE_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR => POP_SCREEN_AUTHORUSERS,
            self::MODULE_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR => POP_SCREEN_AUTHORTAGS,
            self::MODULE_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($module);
    }
}


