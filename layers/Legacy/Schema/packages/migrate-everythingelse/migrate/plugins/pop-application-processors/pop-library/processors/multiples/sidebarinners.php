<?php

class PoP_Module_Processor_SidebarMultipleInners extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR = 'multiple-sectioninner-content-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_POSTS_SIDEBAR = 'multiple-sectioninner-posts-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_CATEGORYPOSTS_SIDEBAR = 'multiple-sectioninner-categoryposts-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_USERS_SIDEBAR = 'multiple-sectioninner-users-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_USERS_NOFILTER_SIDEBAR = 'multiple-sectioninner-users-nofilter-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_TRENDINGTAGS_SIDEBAR = 'multiple-sectioninner-trendingtags-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_TAGS_SIDEBAR = 'multiple-sectioninner-tags-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_AUTHORTAGS_SIDEBAR = 'multiple-sectioninner-authortags-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_MYCONTENT_SIDEBAR = 'multiple-sectioninner-mycontent-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_MYPOSTS_SIDEBAR = 'multiple-sectioninner-myposts-sidebar';
    public final const MODULE_MULTIPLE_SECTIONINNER_MYCATEGORYPOSTS_SIDEBAR = 'multiple-sectioninner-mycategoryposts-sidebar';
    public final const MODULE_MULTIPLE_TAGSECTIONINNER_MAINCONTENT_SIDEBAR = 'multiple-tagsectioninner-mainallcontent-sidebar';
    public final const MODULE_MULTIPLE_TAGSECTIONINNER_CONTENT_SIDEBAR = 'multiple-tagsectioninner-content-sidebar';
    public final const MODULE_MULTIPLE_TAGSECTIONINNER_POSTS_SIDEBAR = 'multiple-tagsectioninner-posts-sidebar';
    public final const MODULE_MULTIPLE_TAGSECTIONINNER_CATEGORYPOSTS_SIDEBAR = 'multiple-tagsectioninner-categoryposts-sidebar';
    public final const MODULE_MULTIPLE_AUTHORSECTIONINNER_MAINCONTENT_SIDEBAR = 'multiple-authorsectioninner-maincontent-sidebar';
    public final const MODULE_MULTIPLE_AUTHORSECTIONINNER_CONTENT_SIDEBAR = 'multiple-authorsectioninner-content-sidebar';
    public final const MODULE_MULTIPLE_AUTHORSECTIONINNER_POSTS_SIDEBAR = 'multiple-authorsectioninner-posts-sidebar';
    public final const MODULE_MULTIPLE_AUTHORSECTIONINNER_CATEGORYPOSTS_SIDEBAR = 'multiple-authorsectioninner-categoryposts-sidebar';
    public final const MODULE_MULTIPLE_HOMESECTIONINNER_CONTENT_SIDEBAR = 'multiple-homesectioninner-content-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_POSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_CATEGORYPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_USERS_NOFILTER_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_TRENDINGTAGS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_TAGS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORTAGS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_MYCONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_MYPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_MYCATEGORYPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAGSECTIONINNER_MAINCONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAGSECTIONINNER_CONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAGSECTIONINNER_POSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAGSECTIONINNER_CATEGORYPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_HOMESECTIONINNER_CONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_MAINCONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_CONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_POSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_CATEGORYPOSTS_SIDEBAR],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
         // Trending Tags has no filter
            case self::COMPONENT_MULTIPLE_SECTIONINNER_TRENDINGTAGS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGS];
                $ret[] = [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_TRENDINGTAGSDESCRIPTION];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_TAGS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGS];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORTAGS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_AUTHORTAGS];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGS];
                break;
                    
            case self::COMPONENT_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_SECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_CONTENT];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_POSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_SECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_POSTS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_CATEGORYPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_SECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_CATEGORYPOSTS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_USERS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_USERS];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_USERS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_USERS_NOFILTER_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_USERS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_MYCONTENT_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_MYCONTENT];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_MYPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_MYPOSTS];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_MYCATEGORYPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_MYCONTENT];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_MYCATEGORYPOSTS];
                break;

            case self::COMPONENT_MULTIPLE_TAGSECTIONINNER_MAINCONTENT_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGSECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGMAINCONTENT];
                break;

            case self::COMPONENT_MULTIPLE_TAGSECTIONINNER_CONTENT_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGSECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGCONTENT];
                break;

            case self::COMPONENT_MULTIPLE_TAGSECTIONINNER_POSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGSECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGPOSTS];
                break;

            case self::COMPONENT_MULTIPLE_TAGSECTIONINNER_CATEGORYPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGSECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGCATEGORYPOSTS];
                break;

            case self::COMPONENT_MULTIPLE_HOMESECTIONINNER_CONTENT_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_HOMESECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_HOMECONTENT];
                break;

            case self::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_MAINCONTENT_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_AUTHORSECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORMAINCONTENT];
                break;

            case self::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_CONTENT_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_AUTHORSECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORCONTENT];
                break;

            case self::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_POSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_AUTHORSECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORPOSTS];
                break;

            case self::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_CATEGORYPOSTS_SIDEBAR:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_AUTHORSECTION];
                $ret[] = [PoP_Module_Processor_CustomDelegatorFilters::class, PoP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORCATEGORYPOSTS];
                break;
        }
        
        return $ret;
    }
}



