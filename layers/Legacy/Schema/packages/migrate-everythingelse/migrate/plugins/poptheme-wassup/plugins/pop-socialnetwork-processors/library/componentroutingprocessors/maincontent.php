<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_SocialNetwork_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $componentVariations = array(
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
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
        }

        // Tag modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);
        $default_format_tagusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGUSERS);

        $routemodules_userdetails = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS],
        );
        foreach ($routemodules_userdetails as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_DETAILS) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_userfullview as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_userthumbnail as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userlist = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST],
        );
        foreach ($routemodules_userlist as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
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
        foreach ($routemodules_details as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userdetails = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
        );
        foreach ($routemodules_userdetails as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_tagdetails = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
        );
        foreach ($routemodules_tagdetails as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authortags == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_simpleview = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_fullview = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_userfullview as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_thumbnail = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_userthumbnail as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_list = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userlist = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
        );
        foreach ($routemodules_userlist as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_taglist = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
        );
        foreach ($routemodules_taglist as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authortags == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
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
        foreach ($routemodules_userdetails as $route => $componentVariation) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_DETAILS) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_userfullview as $route => $componentVariation) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_FULLVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_userthumbnail as $route => $componentVariation) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_THUMBNAIL) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $componentVariation];
            }
        }
        $routemodules_userlist = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST],
        );
        foreach ($routemodules_userlist as $route => $componentVariation) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_LIST) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $componentVariation];
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
		new PoPTheme_Wassup_SocialNetwork_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
