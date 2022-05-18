<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Blog_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_CONTENT_SCROLL_NAVIGATOR = 'block-content-scroll-navigator';
    public final const MODULE_BLOCK_POSTS_SCROLL_NAVIGATOR = 'block-posts-scroll-navigator';
    public final const MODULE_BLOCK_USERS_SCROLL_NAVIGATOR = 'block-users-scroll-navigator';
    public final const MODULE_BLOCK_CONTENT_SCROLL_ADDONS = 'block-content-scroll-addons';
    public final const MODULE_BLOCK_POSTS_SCROLL_ADDONS = 'block-posts-scroll-addons';
    public final const MODULE_BLOCK_USERS_SCROLL_ADDONS = 'block-users-scroll-addons';
    public final const MODULE_BLOCK_HOMECONTENT_SCROLL_DETAILS = 'block-homecontent-scroll-details';
    public final const MODULE_BLOCK_SEARCHCONTENT_SCROLL_DETAILS = 'block-searchcontent-scroll-details';
    public final const MODULE_BLOCK_CONTENT_SCROLL_DETAILS = 'block-content-scroll-details';
    public final const MODULE_BLOCK_POSTS_SCROLL_DETAILS = 'block-posts-scroll-details';
    public final const MODULE_BLOCK_TAGS_SCROLL_DETAILS = 'block-tags-scroll-details';
    public final const MODULE_BLOCK_SEARCHUSERS_SCROLL_DETAILS = 'block-searchusers-scroll-details';
    public final const MODULE_BLOCK_USERS_SCROLL_DETAILS = 'block-users-scroll-details';
    public final const MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS = 'block-authorcontent-scroll-details';
    public final const MODULE_BLOCK_AUTHORPOSTS_SCROLL_DETAILS = 'block-authorposts-scroll-details';
    public final const MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS = 'block-tagcontent-scroll-details';
    public final const MODULE_BLOCK_TAGPOSTS_SCROLL_DETAILS = 'block-tagposts-scroll-details';
    public final const MODULE_BLOCK_HOMECONTENT_SCROLL_SIMPLEVIEW = 'block-homecontent-scroll-simpleview';
    public final const MODULE_BLOCK_SEARCHCONTENT_SCROLL_SIMPLEVIEW = 'block-searchcontent-scroll-simpleview';
    public final const MODULE_BLOCK_CONTENT_SCROLL_SIMPLEVIEW = 'block-content-scroll-simpleview';
    public final const MODULE_BLOCK_POSTS_SCROLL_SIMPLEVIEW = 'block-posts-scroll-simpleview';
    public final const MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW = 'block-authorcontent-scroll-simpleview';
    public final const MODULE_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW = 'block-authorposts-scroll-simpleview';
    public final const MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW = 'block-tagcontent-scroll-simpleview';
    public final const MODULE_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW = 'block-tagposts-scroll-simpleview';
    public final const MODULE_BLOCK_HOMECONTENT_SCROLL_FULLVIEW = 'block-homecontent-scroll-fullview';
    public final const MODULE_BLOCK_SEARCHCONTENT_SCROLL_FULLVIEW = 'block-searchcontent-scroll-fullview';
    public final const MODULE_BLOCK_CONTENT_SCROLL_FULLVIEW = 'block-content-scroll-fullview';
    public final const MODULE_BLOCK_POSTS_SCROLL_FULLVIEW = 'block-posts-scroll-fullview';
    public final const MODULE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW = 'block-searchusers-scroll-fullview';
    public final const MODULE_BLOCK_USERS_SCROLL_FULLVIEW = 'block-users-scroll-fullview';
    public final const MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW = 'block-authorcontent-scroll-fullview';
    public final const MODULE_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW = 'block-authorposts-scroll-fullview';
    public final const MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW = 'block-tagcontent-scroll-fullview';
    public final const MODULE_BLOCK_TAGPOSTS_SCROLL_FULLVIEW = 'block-tagposts-scroll-fullview';
    public final const MODULE_BLOCK_HOMECONTENT_SCROLL_THUMBNAIL = 'block-homecontent-scroll-thumbnail';
    public final const MODULE_BLOCK_SEARCHCONTENT_SCROLL_THUMBNAIL = 'block-searchcontent-scroll-thumbnail';
    public final const MODULE_BLOCK_CONTENT_SCROLL_THUMBNAIL = 'block-content-scroll-thumbnail';
    public final const MODULE_BLOCK_POSTS_SCROLL_THUMBNAIL = 'block-posts-scroll-thumbnail';
    public final const MODULE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL = 'block-searchusers-scroll-thumbnail';
    public final const MODULE_BLOCK_USERS_SCROLL_THUMBNAIL = 'block-users-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL = 'block-authorcontent-scroll-thumbnail';
    public final const MODULE_BLOCK_AUTHORPOSTS_SCROLL_THUMBNAIL = 'block-authorposts-scroll-thumbnail';
    public final const MODULE_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL = 'block-tagcontent-scroll-thumbnail';
    public final const MODULE_BLOCK_TAGPOSTS_SCROLL_THUMBNAIL = 'block-tagposts-scroll-thumbnail';
    public final const MODULE_BLOCK_HOMECONTENT_SCROLL_LIST = 'block-homecontent-scroll-list';
    public final const MODULE_BLOCK_SEARCHCONTENT_SCROLL_LIST = 'block-searchcontent-scroll-list';
    public final const MODULE_BLOCK_CONTENT_SCROLL_LIST = 'block-content-scroll-list';
    public final const MODULE_BLOCK_POSTS_SCROLL_LIST = 'block-posts-scroll-list';
    public final const MODULE_BLOCK_TAGS_SCROLL_LIST = 'block-tags-scroll-list';
    public final const MODULE_BLOCK_SEARCHUSERS_SCROLL_LIST = 'block-searchusers-scroll-list';
    public final const MODULE_BLOCK_USERS_SCROLL_LIST = 'block-users-scroll-list';
    public final const MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST = 'block-authorcontent-scroll-list';
    public final const MODULE_BLOCK_AUTHORPOSTS_SCROLL_LIST = 'block-authorposts-scroll-list';
    public final const MODULE_BLOCK_TAGCONTENT_SCROLL_LIST = 'block-tagcontent-scroll-list';
    public final const MODULE_BLOCK_TAGPOSTS_SCROLL_LIST = 'block-tagposts-scroll-list';
    public final const MODULE_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST = 'block-authorcontent-scroll-fixedlist';
    public final const MODULE_BLOCK_USERS_CAROUSEL = 'block-users-carousel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_CONTENT_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_POSTS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_USERS_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_BLOCK_CONTENT_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_POSTS_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_USERS_SCROLL_ADDONS],
            [self::class, self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_HOMECONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_CONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_POSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_SEARCHUSERS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_USERS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TAGS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_HOMECONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_CONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_POSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_HOMECONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_CONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_POSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_USERS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_HOMECONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_CONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_POSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_USERS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_HOMECONTENT_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_CONTENT_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_POSTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_SEARCHUSERS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_USERS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST],
            [self::class, self::MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TAGPOSTS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGPOSTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGPOSTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_BLOCK_TAGCONTENT_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_TAGPOSTS_SCROLL_LIST],
            [self::class, self::MODULE_BLOCK_USERS_CAROUSEL],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_DETAILS => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_LIST => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_THUMBNAIL => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_CONTENT_SCROLL_ADDONS => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_CONTENT_SCROLL_DETAILS => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_CONTENT_SCROLL_FULLVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_CONTENT_SCROLL_LIST => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_CONTENT_SCROLL_NAVIGATOR => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_CONTENT_SCROLL_SIMPLEVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_CONTENT_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_POSTS_SCROLL_ADDONS => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_POSTS_SCROLL_DETAILS => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_POSTS_SCROLL_FULLVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_POSTS_SCROLL_LIST => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_POSTS_SCROLL_NAVIGATOR => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_POSTS_SCROLL_SIMPLEVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_POSTS_SCROLL_THUMBNAIL => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_DETAILS => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_FULLVIEW => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_LIST => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_SIMPLEVIEW => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_SEARCHCONTENT,
            self::MODULE_BLOCK_SEARCHUSERS_SCROLL_DETAILS => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_BLOCK_SEARCHUSERS_SCROLL_LIST => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_LIST => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL => POP_BLOG_ROUTE_CONTENT,
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_DETAILS => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_FULLVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_LIST => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_THUMBNAIL => PostsModuleConfiguration::getPostsRoute(),
            self::MODULE_BLOCK_TAGS_SCROLL_DETAILS => PostTagsModuleConfiguration::getPostTagsRoute() ,
            self::MODULE_BLOCK_TAGS_SCROLL_LIST => PostTagsModuleConfiguration::getPostTagsRoute() ,
            self::MODULE_BLOCK_USERS_CAROUSEL => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_BLOCK_USERS_CAROUSEL => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_BLOCK_USERS_SCROLL_ADDONS => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_BLOCK_USERS_SCROLL_DETAILS => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_BLOCK_USERS_SCROLL_FULLVIEW => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_BLOCK_USERS_SCROLL_LIST => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_BLOCK_USERS_SCROLL_NAVIGATOR => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_BLOCK_USERS_SCROLL_THUMBNAIL => UsersModuleConfiguration::getUsersRoute(),
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_BLOCK_CONTENT_SCROLL_NAVIGATOR => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_CONTENT_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_POSTS_SCROLL_NAVIGATOR => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_POSTS_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_USERS_SCROLL_NAVIGATOR => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_SCROLL_NAVIGATOR],
            self::MODULE_BLOCK_CONTENT_SCROLL_ADDONS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_CONTENT_SCROLL_ADDONS],
            self::MODULE_BLOCK_POSTS_SCROLL_ADDONS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_POSTS_SCROLL_ADDONS],
            self::MODULE_BLOCK_USERS_SCROLL_ADDONS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_SCROLL_ADDONS],
            self::MODULE_BLOCK_HOMECONTENT_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HOMECONTENT_SCROLL_DETAILS],
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS],
            self::MODULE_BLOCK_CONTENT_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_CONTENT_SCROLL_DETAILS],
            self::MODULE_BLOCK_POSTS_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_POSTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_TAGS_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGS_SCROLL_DETAILS],
            self::MODULE_BLOCK_SEARCHUSERS_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLL_DETAILS],
            self::MODULE_BLOCK_USERS_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_SCROLL_DETAILS],
            self::MODULE_BLOCK_HOMECONTENT_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_CONTENT_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_POSTS_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_POSTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_HOMECONTENT_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_CONTENT_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_CONTENT_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_POSTS_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_POSTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_USERS_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_HOMECONTENT_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_CONTENT_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_CONTENT_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_POSTS_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_POSTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_USERS_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_HOMECONTENT_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HOMECONTENT_SCROLL_LIST],
            self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHCONTENT_SCROLL_LIST],
            self::MODULE_BLOCK_CONTENT_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_CONTENT_SCROLL_LIST],
            self::MODULE_BLOCK_POSTS_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_POSTS_SCROLL_LIST],
            self::MODULE_BLOCK_TAGS_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGS_SCROLL_LIST],
            self::MODULE_BLOCK_SEARCHUSERS_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLL_LIST],
            self::MODULE_BLOCK_USERS_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST],
            self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FIXEDLIST],
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGCONTENT_SCROLL_DETAILS],
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPOSTS_SCROLL_DETAILS],
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGCONTENT_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPOSTS_SCROLL_SIMPLEVIEW],
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGCONTENT_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPOSTS_SCROLL_FULLVIEW],
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGCONTENT_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPOSTS_SCROLL_THUMBNAIL],
            self::MODULE_BLOCK_TAGCONTENT_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGCONTENT_SCROLL_LIST],
            self::MODULE_BLOCK_TAGPOSTS_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGPOSTS_SCROLL_LIST],
            self::MODULE_BLOCK_USERS_CAROUSEL => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_CAROUSEL],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    protected function getSectionfilterModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_CONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_CONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_CONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_CONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_CONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_LIST:
                if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy() && PoP_ApplicationProcessors_Utils::addSections()) {
                    return [PoP_Module_Processor_InstantaneousFilters::class, PoP_Module_Processor_InstantaneousFilters::MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS];
                }
                break;

            case self::MODULE_BLOCK_POSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_POSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_POSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_POSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_POSTS_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_InstantaneousFilters::class, PoP_Module_Processor_InstantaneousFilters::MODULE_INSTANTANEOUSFILTER_POSTSECTIONS];
        }

        return parent::getSectionfilterModule($componentVariation);
    }

    protected function getDescriptionBottom(array $componentVariation, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return sprintf(
                    '<br/><p class="text-center"><a href="%s">%s</a></p>',
                    $userTypeAPI->getUserURL($author),
                    TranslationAPIFacade::getInstance()->__('View all Content ', 'poptheme-wassup').'<i class="fa fa-fw fa-arrow-right"></i>'
                );
        }

        return parent::getDescriptionBottom($componentVariation, $props);
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKAUTHORPOSTLIST];

            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_CONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_POSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_CONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_POSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_CONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_POSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_CONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_POSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_CONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_POSTS_SCROLL_LIST:
            case self::MODULE_BLOCK_SEARCHCONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];

            case self::MODULE_BLOCK_TAGS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_TAGLIST];

            case self::MODULE_BLOCK_SEARCHUSERS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_USERS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_USERS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_USERS_SCROLL_THUMBNAIL:
            case self::MODULE_BLOCK_SEARCHUSERS_SCROLL_LIST:
            case self::MODULE_BLOCK_USERS_SCROLL_LIST:
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function getLatestcountSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_HOMECONTENT_SCROLL_LIST:
            case self::MODULE_BLOCK_CONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_CONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_CONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_CONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_LatestCounts::class, PoP_Module_Processor_LatestCounts::MODULE_LATESTCOUNT_CONTENT];

            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_LatestCounts::class, PoP_Module_Processor_LatestCounts::MODULE_LATESTCOUNT_TAG_CONTENT];

            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_LatestCounts::class, PoP_Module_Processor_LatestCounts::MODULE_LATESTCOUNT_AUTHOR_CONTENT];

            case self::MODULE_BLOCK_POSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_POSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_POSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_POSTS_SCROLL_LIST:
                return [PoPThemeWassup_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_POSTS];

            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_AUTHORPOSTS_SCROLL_LIST:
                return [PoPThemeWassup_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_AUTHOR_POSTS];

            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_FULLVIEW:
            case self::MODULE_BLOCK_TAGPOSTS_SCROLL_LIST:
                return [PoPThemeWassup_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_TAG_POSTS];
        }

        return parent::getLatestcountSubmodule($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FIXEDLIST:
                return getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Latest Content', 'poptheme-wassup');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_USERS_CAROUSEL:
                $this->appendProp($componentVariation, $props, 'class', 'pop-block-carousel block-users-carousel');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



