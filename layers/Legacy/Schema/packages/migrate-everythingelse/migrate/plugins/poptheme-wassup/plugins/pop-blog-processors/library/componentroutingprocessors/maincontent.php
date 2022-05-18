<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_Blog_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        // Page modules
        $routemodules = array(
            POP_BLOG_ROUTE_COMMENTS => [PoP_Module_Processor_CommentsBlocks::class, PoP_Module_Processor_CommentsBlocks::MODULE_BLOCK_COMMENTS_SCROLL],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        // Feeds
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_tags = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGS);

        // Navigator
        $routemodules_navigator = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_CONTENT_SCROLL_NAVIGATOR],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_POSTS_SCROLL_NAVIGATOR],
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_USERS_SCROLL_NAVIGATOR],
        );
        foreach ($routemodules_navigator as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_section == POP_FORMAT_NAVIGATOR) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        // Addons
        $routemodules_addons = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_CONTENT_SCROLL_ADDONS],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_POSTS_SCROLL_ADDONS],
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_USERS_SCROLL_ADDONS],
        );
        foreach ($routemodules_addons as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        $routemodules_carousels = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_USERS_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_users == POP_FORMAT_CAROUSEL) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_CONTENT_SCROLL_THUMBNAIL],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_POSTS_SCROLL_THUMBNAIL],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHCONTENT_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_CONTENT_SCROLL_LIST],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_POSTS_SCROLL_LIST],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHCONTENT_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        $routemodules_typeahead = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_CONTENT_TYPEAHEAD],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_POSTS_TYPEAHEAD],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHCONTENT_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        $routemodules_usertypeahead = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_TYPEAHEAD],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_TYPEAHEAD],
        );
        foreach ($routemodules_usertypeahead as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_users == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        $routemodules_mentions = array(
            PostTagsModuleConfiguration::getPostTagsRoute() => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGS_MENTIONS],
        );
        foreach ($routemodules_mentions as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MENTION,
                ],
            ];
            if ($default_format_tags == POP_FORMAT_MENTION) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_usermentions = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_USERS_MENTIONS],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_MENTIONS],
        );
        foreach ($routemodules_usermentions as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MENTION,
                ],
            ];
            if ($default_format_users == POP_FORMAT_MENTION) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        $routemodules_details = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_CONTENT_SCROLL_DETAILS],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_POSTS_SCROLL_DETAILS],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHCONTENT_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_userdetails = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_USERS_SCROLL_DETAILS],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHUSERS_SCROLL_DETAILS],
        );
        foreach ($routemodules_userdetails as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_users == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_tagdetails = array(
            PostTagsModuleConfiguration::getPostTagsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGS_SCROLL_DETAILS],
        );
        foreach ($routemodules_tagdetails as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_tags == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_CONTENT_SCROLL_SIMPLEVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_POSTS_SCROLL_SIMPLEVIEW],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_CONTENT_SCROLL_FULLVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_POSTS_SCROLL_FULLVIEW],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHCONTENT_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_userfullview = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_USERS_SCROLL_FULLVIEW],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_userfullview as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_users == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_userthumbnail = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_USERS_SCROLL_THUMBNAIL],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_userthumbnail as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_users == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_userlist = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_USERS_SCROLL_LIST],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SEARCHUSERS_SCROLL_LIST],
        );
        foreach ($routemodules_userlist as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_users == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_taglist = array(
            PostTagsModuleConfiguration::getPostTagsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGS_SCROLL_LIST],
        );
        foreach ($routemodules_taglist as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_tags == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_carousels_home = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_USERS_CAROUSEL],
        );
        foreach ($routemodules_carousels_home as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_users == POP_FORMAT_CAROUSEL) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        // Author route modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        $routemodules_details = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPOSTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORPOSTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }

        // Tag modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);

        $routemodules_details = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPOSTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_LIST],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGPOSTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_Blog_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
