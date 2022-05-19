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
        $routeComponents = array(
            POP_BLOG_ROUTE_COMMENTS => [PoP_Module_Processor_CommentsBlocks::class, PoP_Module_Processor_CommentsBlocks::COMPONENT_BLOCK_COMMENTS_SCROLL],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        // Feeds
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_tags = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGS);

        // Navigator
        $routeComponents_navigator = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_CONTENT_SCROLL_NAVIGATOR],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_POSTS_SCROLL_NAVIGATOR],
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_USERS_SCROLL_NAVIGATOR],
        );
        foreach ($routeComponents_navigator as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_section == POP_FORMAT_NAVIGATOR) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        // Addons
        $routeComponents_addons = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_CONTENT_SCROLL_ADDONS],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_POSTS_SCROLL_ADDONS],
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_USERS_SCROLL_ADDONS],
        );
        foreach ($routeComponents_addons as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_carousels = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_USERS_CAROUSEL],
        );
        foreach ($routeComponents_carousels as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_users == POP_FORMAT_CAROUSEL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_CONTENT_SCROLL_THUMBNAIL],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_POSTS_SCROLL_THUMBNAIL],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHCONTENT_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_CONTENT_SCROLL_LIST],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_POSTS_SCROLL_LIST],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHCONTENT_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_typeahead = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_TYPEAHEAD],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_TYPEAHEAD],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_TYPEAHEAD],
        );
        foreach ($routeComponents_typeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_usertypeahead = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_TYPEAHEAD],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_TYPEAHEAD],
        );
        foreach ($routeComponents_usertypeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_users == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_mentions = array(
            PostTagsModuleConfiguration::getPostTagsRoute() => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGS_MENTIONS],
        );
        foreach ($routeComponents_mentions as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MENTION,
                ],
            ];
            if ($default_format_tags == POP_FORMAT_MENTION) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_usermentions = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_MENTIONS],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_MENTIONS],
        );
        foreach ($routeComponents_usermentions as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MENTION,
                ],
            ];
            if ($default_format_users == POP_FORMAT_MENTION) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_details = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_CONTENT_SCROLL_DETAILS],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_POSTS_SCROLL_DETAILS],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHCONTENT_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userdetails = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_USERS_SCROLL_DETAILS],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHUSERS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_userdetails as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_users == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_tagdetails = array(
            PostTagsModuleConfiguration::getPostTagsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_tagdetails as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_tags == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_simpleview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_CONTENT_SCROLL_SIMPLEVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_POSTS_SCROLL_SIMPLEVIEW],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
        );
        foreach ($routeComponents_simpleview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_CONTENT_SCROLL_FULLVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_POSTS_SCROLL_FULLVIEW],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHCONTENT_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userfullview = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_USERS_SCROLL_FULLVIEW],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_userfullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_users == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userthumbnail = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_USERS_SCROLL_THUMBNAIL],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_userthumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_users == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userlist = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_USERS_SCROLL_LIST],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SEARCHUSERS_SCROLL_LIST],
        );
        foreach ($routeComponents_userlist as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_users == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_taglist = array(
            PostTagsModuleConfiguration::getPostTagsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGS_SCROLL_LIST],
        );
        foreach ($routeComponents_taglist as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_tags == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_carousels_home = array(
            UsersModuleConfiguration::getUsersRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_USERS_CAROUSEL],
        );
        foreach ($routeComponents_carousels_home as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_users == POP_FORMAT_CAROUSEL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        // Author route modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        $routeComponents_details = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCONTENT_SCROLL_DETAILS],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORPOSTS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_simpleview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routeComponents_simpleview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCONTENT_SCROLL_LIST],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORPOSTS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }

        // Tag modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);

        $routeComponents_details = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_DETAILS],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGPOSTS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_simpleview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routeComponents_simpleview as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_FULLVIEW],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_LIST],
            PostsModuleConfiguration::getPostsRoute() => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGPOSTS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
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
