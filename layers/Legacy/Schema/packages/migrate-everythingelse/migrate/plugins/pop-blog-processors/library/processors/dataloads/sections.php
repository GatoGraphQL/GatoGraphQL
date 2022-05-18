<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Blog_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD = 'dataload-searchcontent-typeahead';
    public final const MODULE_DATALOAD_CONTENT_TYPEAHEAD = 'dataload-content-typeahead';
    public final const MODULE_DATALOAD_POSTS_TYPEAHEAD = 'dataload-posts-typeahead';
    public final const MODULE_DATALOAD_SEARCHUSERS_TYPEAHEAD = 'dataload-searchusers-typeahead';
    public final const MODULE_DATALOAD_USERS_TYPEAHEAD = 'dataload-users-typeahead';
    public final const MODULE_DATALOAD_TAGS_TYPEAHEAD = 'dataload-tags-typeahead';
    public final const MODULE_DATALOAD_SEARCHUSERS_MENTIONS = 'dataload-searchusers-mentions';
    public final const MODULE_DATALOAD_USERS_MENTIONS = 'dataload-users-mentions';
    public final const MODULE_DATALOAD_TAGS_MENTIONS = 'dataload-tags-mentions';
    public final const MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR = 'dataload-content-scroll-navigator';
    public final const MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR = 'dataload-posts-scroll-navigator';
    public final const MODULE_DATALOAD_USERS_SCROLL_NAVIGATOR = 'dataload-users-scroll-navigator';
    public final const MODULE_DATALOAD_CONTENT_SCROLL_ADDONS = 'dataload-content-scroll-addons';
    public final const MODULE_DATALOAD_POSTS_SCROLL_ADDONS = 'dataload-posts-scroll-addons';
    public final const MODULE_DATALOAD_USERS_SCROLL_ADDONS = 'dataload-users-scroll-addons';
    public final const MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS = 'dataload-homecontent-scroll-details';
    public final const MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS = 'dataload-searchcontent-scroll-details';
    public final const MODULE_DATALOAD_CONTENT_SCROLL_DETAILS = 'dataload-content-scroll-details';
    public final const MODULE_DATALOAD_POSTS_SCROLL_DETAILS = 'dataload-posts-scroll-details';
    public final const MODULE_DATALOAD_TAGS_SCROLL_DETAILS = 'dataload-tags-scroll-details';
    public final const MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS = 'dataload-searchusers-scroll-details';
    public final const MODULE_DATALOAD_USERS_SCROLL_DETAILS = 'dataload-users-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS = 'dataload-authorcontent-scroll-details';
    public final const MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS = 'dataload-authorposts-scroll-details';
    public final const MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS = 'dataload-tagcontent-scroll-details';
    public final const MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS = 'dataload-tagposts-scroll-details';
    public final const MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW = 'dataload-homecontent-scroll-simpleview';
    public final const MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW = 'dataload-searchcontent-scroll-simpleview';
    public final const MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW = 'dataload-content-scroll-simpleview';
    public final const MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW = 'dataload-posts-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW = 'dataload-authorcontent-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW = 'dataload-authorposts-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW = 'dataload-tagcontent-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW = 'dataload-tagposts-scroll-simpleview';
    public final const MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW = 'dataload-homecontent-scroll-fullview';
    public final const MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW = 'dataload-searchcontent-scroll-fullview';
    public final const MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW = 'dataload-content-scroll-fullview';
    public final const MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW = 'dataload-posts-scroll-fullview';
    public final const MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW = 'dataload-searchusers-scroll-fullview';
    public final const MODULE_DATALOAD_USERS_SCROLL_FULLVIEW = 'dataload-users-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW = 'dataload-authorcontent-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW = 'dataload-authorposts-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW = 'dataload-tagcontent-scroll-fullview';
    public final const MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW = 'dataload-tagposts-scroll-fullview';
    public final const MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL = 'dataload-homecontent-scroll-thumbnail';
    public final const MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL = 'dataload-searchcontent-scroll-thumbnail';
    public final const MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL = 'dataload-content-scroll-thumbnail';
    public final const MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL = 'dataload-posts-scroll-thumbnail';
    public final const MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL = 'dataload-searchusers-scroll-thumbnail';
    public final const MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL = 'dataload-users-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL = 'dataload-authorcontent-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL = 'dataload-authorposts-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL = 'dataload-tagcontent-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL = 'dataload-tagposts-scroll-thumbnail';
    public final const MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST = 'dataload-homecontent-scroll-list';
    public final const MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST = 'dataload-searchcontent-scroll-list';
    public final const MODULE_DATALOAD_CONTENT_SCROLL_LIST = 'dataload-content-scroll-list';
    public final const MODULE_DATALOAD_POSTS_SCROLL_LIST = 'dataload-posts-scroll-list';
    public final const MODULE_DATALOAD_TAGS_SCROLL_LIST = 'dataload-tags-scroll-list';
    public final const MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST = 'dataload-searchusers-scroll-list';
    public final const MODULE_DATALOAD_USERS_SCROLL_LIST = 'dataload-users-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST = 'dataload-authorcontent-scroll-list';
    public final const MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST = 'dataload-authorposts-scroll-list';
    public final const MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST = 'dataload-tagcontent-scroll-list';
    public final const MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST = 'dataload-tagposts-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST = 'dataload-authorcontent-scroll-fixedlist';
    public final const MODULE_DATALOAD_USERS_CAROUSEL = 'dataload-users-carousel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CONTENT_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_POSTS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_USERS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_TAGS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_TAGS_MENTIONS],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_MENTIONS],
            [self::class, self::MODULE_DATALOAD_USERS_MENTIONS],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST],

            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_USERS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_CONTENT_SCROLL_ADDONS => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_CONTENT_SCROLL_LIST => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_CONTENT_TYPEAHEAD => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_POSTS_SCROLL_ADDONS => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_POSTS_SCROLL_DETAILS => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_POSTS_SCROLL_LIST => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_POSTS_TYPEAHEAD => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_DATALOAD_SEARCHUSERS_MENTIONS => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_DATALOAD_SEARCHUSERS_TYPEAHEAD => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_DATALOAD_TAGS_MENTIONS => PostTagsModuleConfiguration::getPostTagsRoute() ,
            self::MODULE_DATALOAD_TAGS_SCROLL_DETAILS => PostTagsModuleConfiguration::getPostTagsRoute() ,
            self::MODULE_DATALOAD_TAGS_SCROLL_LIST => PostTagsModuleConfiguration::getPostTagsRoute() ,
            self::MODULE_DATALOAD_USERS_CAROUSEL => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_CAROUSEL => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_MENTIONS => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_SCROLL_ADDONS => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_SCROLL_DETAILS => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_SCROLL_FULLVIEW => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_SCROLL_LIST => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_SCROLL_NAVIGATOR => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_TYPEAHEAD => UsersModuleConfiguration::getUsersRoute(),
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

            /*********************************************
         * Typeaheads
         *********************************************/
            // Straight to the layout
            self::MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CONTENT_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_POSTS_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],

            self::MODULE_DATALOAD_SEARCHUSERS_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_USERS_TYPEAHEAD => [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_TAGS_TYPEAHEAD => [PoP_Module_Processor_TagTypeaheadComponentLayouts::class, PoP_Module_Processor_TagTypeaheadComponentLayouts::MODULE_LAYOUTTAG_TYPEAHEAD_COMPONENT],

            /*********************************************
         * Mentions
         *********************************************/
            // Straight to the layout
            self::MODULE_DATALOAD_TAGS_MENTIONS => [PoP_Module_Processor_TagMentionComponentLayouts::class, PoP_Module_Processor_TagMentionComponentLayouts::MODULE_LAYOUTTAG_MENTION_COMPONENT],
            self::MODULE_DATALOAD_SEARCHUSERS_MENTIONS => [PoP_Module_Processor_UserMentionComponentLayouts::class, PoP_Module_Processor_UserMentionComponentLayouts::MODULE_LAYOUTUSER_MENTION_COMPONENT],
            self::MODULE_DATALOAD_USERS_MENTIONS => [PoP_Module_Processor_UserMentionComponentLayouts::class, PoP_Module_Processor_UserMentionComponentLayouts::MODULE_LAYOUTUSER_MENTION_COMPONENT],

            self::MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_NAVIGATOR],
            self::MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_USERS_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_NAVIGATOR],
            self::MODULE_DATALOAD_CONTENT_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_ADDONS],
            self::MODULE_DATALOAD_POSTS_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_USERS_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_ADDONS],

            self::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_DETAILS],
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_DETAILS],
            self::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_DETAILS],
            self::MODULE_DATALOAD_POSTS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_TAGS_DETAILS],

            self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_USERS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],

            self::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_SIMPLEVIEW],
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_SIMPLEVIEW],
            self::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_SIMPLEVIEW],
            self::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_SIMPLEVIEW],

            self::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_FULLVIEW],
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_FULLVIEW],
            self::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_FULLVIEW],
            self::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],

            self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_USERS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],

            self::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_THUMBNAIL],
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_THUMBNAIL],
            self::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_THUMBNAIL],
            self::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],

            self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],

            self::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_LIST],
            self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_LIST],
            self::MODULE_DATALOAD_CONTENT_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_LIST],
            self::MODULE_DATALOAD_POSTS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_TAGS_LIST],

            self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
            self::MODULE_DATALOAD_USERS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],

            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_DETAILS],
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],

            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_SIMPLEVIEW],

            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORCONTENT_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],

            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],

            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_LIST],
            self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],

            self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_LIST],

            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_DETAILS],
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],

            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_SIMPLEVIEW],

            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_FULLVIEW],
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],

            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_THUMBNAIL],
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],

            self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_CONTENT_LIST],
            self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],

            self::MODULE_DATALOAD_USERS_CAROUSEL => [PoP_Module_Processor_CustomCarousels::class, PoP_Module_Processor_CustomCarousels::MODULE_CAROUSEL_USERS],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_CONTENT_TYPEAHEAD:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CONTENT];

            case self::MODULE_DATALOAD_POSTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_POSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_POSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_POSTS];

            case self::MODULE_DATALOAD_TAGS_TYPEAHEAD:
            case self::MODULE_DATALOAD_TAGS_MENTIONS:
            case self::MODULE_DATALOAD_TAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGS];

            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCONTENT];

            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGCONTENT];

            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORPOSTS];

            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGPOSTS];

            case self::MODULE_DATALOAD_USERS_TYPEAHEAD:
            case self::MODULE_DATALOAD_SEARCHUSERS_TYPEAHEAD:
            case self::MODULE_DATALOAD_USERS_MENTIONS:
            case self::MODULE_DATALOAD_SEARCHUSERS_MENTIONS:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_USERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_USERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_USERS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $navigators = array(
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_ADDONS],
        );
        $details = array(
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGS_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW],

            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],

            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_POSTS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST],
        );
        $fixedlists = array(
            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST],
        );
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CONTENT_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_POSTS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_USERS_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_TAGS_TYPEAHEAD],
        );
        $mentions = array(
            [self::class, self::MODULE_DATALOAD_TAGS_MENTIONS],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_MENTIONS],
            [self::class, self::MODULE_DATALOAD_USERS_MENTIONS],
        );
        $carousels = array(
            [self::class, self::MODULE_DATALOAD_USERS_CAROUSEL],
        );
        if (in_array($module, $navigators)) {
            $format = POP_FORMAT_NAVIGATOR;
        } elseif (in_array($module, $addons)) {
            $format = POP_FORMAT_ADDONS;
        } elseif (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists) || in_array($module, $fixedlists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($module, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        } elseif (in_array($module, $mentions)) {
            $format = POP_FORMAT_MENTION;
        } elseif (in_array($module, $carousels)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST:
    //             return RequestNature::HOME;

    //         case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($module);
    // }


    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_POSTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_POSTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_POSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_POSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST:
                if ($categories = gdDataloadAllcontentCategories()) {
                    $ret['category-in'] = $categories;
                }
                break;

            case self::MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_CONTENT_TYPEAHEAD:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST:
                PoP_Application_SectionUtils::addDataloadqueryargsAllcontent($ret);
                break;

            case self::MODULE_DATALOAD_USERS_CAROUSEL:
                $ret['orderby'] = NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:users:registrationdate');
                $ret['order'] = 'DESC';
                break;
        }

        $fixedlists = array(
            [self::class, self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST],
        );

        if (in_array($module, $fixedlists)) {
            $ret['limit'] = 4;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST:
                PoP_Application_SectionUtils::addDataloadqueryargsAllcontentBysingletag($ret);
                break;

            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_POSTS_TYPEAHEAD:
            case self::MODULE_DATALOAD_POSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_POSTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_POSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST:
                return $this->instanceManager->getInstance(PostObjectTypeResolver::class);

            case self::MODULE_DATALOAD_TAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGS_TYPEAHEAD:
            case self::MODULE_DATALOAD_TAGS_MENTIONS:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::MODULE_DATALOAD_CONTENT_TYPEAHEAD:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST:
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();

            case self::MODULE_DATALOAD_SEARCHUSERS_TYPEAHEAD:
            case self::MODULE_DATALOAD_USERS_TYPEAHEAD:
            case self::MODULE_DATALOAD_SEARCHUSERS_MENTIONS:
            case self::MODULE_DATALOAD_USERS_MENTIONS:
            case self::MODULE_DATALOAD_USERS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_USERS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_USERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_USERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_USERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_USERS_CAROUSEL:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function getImmutableHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = parent::getImmutableHeaddatasetmoduleDataProperties($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST:
                // Don't bring anymore
                $ret[GD_DATALOAD_QUERYHANDLERPROPERTY_LIST_STOPFETCHING] = true;
                break;
        }

        return $ret;
    }

    public function initRequestProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST:
                // Search: don't bring anything unless we're filtering (no results initially)
                // if ($filter_module = $this->getFilterSubmodule($module)) {
                //     $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                //     $filter = $componentprocessor_manager->getProcessor($filter_module)->getFilter($filter_module);
                // }
                // if (!$filter || !\PoP\Engine\FilterUtils::filteringBy($filter)) {
                if (!$this->getActiveDataloadQueryArgsFilteringComponentVariations($module)) {
                    $this->setProp($module, $props, 'skip-data-load', true);
                }
                break;
        }

        parent::initRequestProps($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_POSTS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_POSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_POSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('posts', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_TAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('tags', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('results', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_USERS_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_USERS_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_USERS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_USERS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_USERS_SCROLL_LIST:
            case self::MODULE_DATALOAD_USERS_CAROUSEL:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



