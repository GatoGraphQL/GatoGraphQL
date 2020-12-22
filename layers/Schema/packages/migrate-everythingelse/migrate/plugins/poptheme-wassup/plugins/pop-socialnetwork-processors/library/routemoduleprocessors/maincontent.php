<?php

use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;

class PoPTheme_Wassup_SocialNetwork_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_SOCIALNETWORK_ROUTE_CONTACTUSER => [PoP_SocialNetwork_Module_Processor_Blocks::class, PoP_SocialNetwork_Module_Processor_Blocks::MODULE_BLOCK_CONTACTUSER],
            POP_SOCIALNETWORK_ROUTE_FOLLOWUSER => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_FOLLOWUSER],
            POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_UNFOLLOWUSER],
            POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_RECOMMENDPOST],
            POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_UNRECOMMENDPOST],
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_SUBSCRIBETOTAG],
            POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_UNSUBSCRIBEFROMTAG],
            POP_SOCIALNETWORK_ROUTE_UPVOTEPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_UPVOTEPOST],
            POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_UNDOUPVOTEPOST],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_DOWNVOTEPOST],
            POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::MODULE_BLOCK_UNDODOWNVOTEPOST],
        );
        foreach ($modules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
        }

        // Tag modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);
        $default_format_tagusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGUSERS);

        $routemodules_userdetails = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS],
        );
        foreach ($routemodules_userdetails as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_DETAILS) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_userfullview as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_FULLVIEW) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_userthumbnail as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_THUMBNAIL) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_userlist = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST],
        );
        foreach ($routemodules_userlist as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_LIST) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }

        // Author route modules
        $default_format_authorusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        $default_format_authortags = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORTAGS);
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_highlights = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_HIGHLIGHTS);

        $routemodules_details = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_userdetails = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
        );
        foreach ($routemodules_userdetails as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_DETAILS) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_tagdetails = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
        );
        foreach ($routemodules_tagdetails as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authortags == POP_FORMAT_DETAILS) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_userfullview as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_FULLVIEW) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_userthumbnail as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_THUMBNAIL) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_userlist = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
        );
        foreach ($routemodules_userlist as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_LIST) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_taglist = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
        );
        foreach ($routemodules_taglist as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authortags == POP_FORMAT_LIST) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }

        // Single route modules
        $default_format_singlesection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLESECTION);
        $default_format_singleusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEUSERS);
        $default_format_singlehighlights = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEHIGHLIGHTS);

        $routemodules_userdetails = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
        );
        foreach ($routemodules_userdetails as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_DETAILS) {
                $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }
        $routemodules_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_userfullview as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_FULLVIEW) {
                $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }
        $routemodules_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_userthumbnail as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_THUMBNAIL) {
                $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }
        $routemodules_userlist = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST],
        );
        foreach ($routemodules_userlist as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_LIST) {
                $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new PoPTheme_Wassup_SocialNetwork_Module_MainContentRouteModuleProcessor()
	);
}, 200);
