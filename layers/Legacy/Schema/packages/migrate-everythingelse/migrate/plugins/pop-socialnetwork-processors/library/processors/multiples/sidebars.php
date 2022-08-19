<?php
use PoP\ComponentModel\State\ApplicationState;

class PoP_SocialNetwork_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR = 'multiple-authorfollowers-sidebar';
    public final const COMPONENT_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR = 'multiple-authorfollowingusers-sidebar';
    public final const COMPONENT_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR = 'multiple-authorsubscribedtotags-sidebar';
    public final const COMPONENT_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR = 'multiple-authorrecommendedposts-sidebar';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR,
            self::COMPONENT_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR,
            self::COMPONENT_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR,
            self::COMPONENT_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
         // Add also the filter block for the Single Related Content, etc
            case self::COMPONENT_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $filters = array(
                    self::COMPONENT_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORTAGS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                );
                if ($filter = $filters[$component->name] ?? null) {
                    $ret[] = $filter;
                }

                // Allow URE to add the Organization/Individual sidebars below
                $ret = \PoP\Root\App::applyFilters(
                    'PoP_UserCommunities_Module_Processor_SidebarMultiples:sidebar-layouts',
                    $ret,
                    $author,
                    $component
                );
                break;
        }

        return $ret;
    }

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR => POP_SCREEN_AUTHORUSERS,
            self::COMPONENT_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR => POP_SCREEN_AUTHORUSERS,
            self::COMPONENT_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR => POP_SCREEN_AUTHORTAGS,
            self::COMPONENT_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
        );
        if ($screen = $screens[$component->name] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }
}


