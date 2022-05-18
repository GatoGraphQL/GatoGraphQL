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

        $components = array(
            POP_SOCIALNETWORK_ROUTE_CONTACTUSER => [PoP_SocialNetwork_Module_Processor_Blocks::class, PoP_SocialNetwork_Module_Processor_Blocks::COMPONENT_BLOCK_CONTACTUSER],
            POP_SOCIALNETWORK_ROUTE_FOLLOWUSER => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_FOLLOWUSER],
            POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_UNFOLLOWUSER],
            POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_RECOMMENDPOST],
            POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_UNRECOMMENDPOST],
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_SUBSCRIBETOTAG],
            POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_UNSUBSCRIBEFROMTAG],
            POP_SOCIALNETWORK_ROUTE_UPVOTEPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_UPVOTEPOST],
            POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_UNDOUPVOTEPOST],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_DOWNVOTEPOST],
            POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST => [PoP_Module_Processor_FunctionsBlocks::class, PoP_Module_Processor_FunctionsBlocks::COMPONENT_BLOCK_UNDODOWNVOTEPOST],
        );
        foreach ($components as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        // Tag modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);
        $default_format_tagusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGUSERS);

        $routeComponents_userdetails = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_userdetails as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_DETAILS) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_userfullview as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_userthumbnail as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userlist = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST],
        );
        foreach ($routeComponents_userlist as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }

        // Author route modules
        $default_format_authorusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        $default_format_authortags = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORTAGS);
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);
        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_highlights = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_HIGHLIGHTS);

        $routeComponents_details = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
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
        $routeComponents_userdetails = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_userdetails as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_tagdetails = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_tagdetails as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authortags == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_simpleview = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
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
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
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
        $routeComponents_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_userfullview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
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
        $routeComponents_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_userthumbnail as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
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
        $routeComponents_userlist = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
        );
        foreach ($routeComponents_userlist as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_taglist = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
        );
        foreach ($routeComponents_taglist as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authortags == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }

        // Single route modules
        $default_format_singlesection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLESECTION);
        $default_format_singleusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEUSERS);
        $default_format_singlehighlights = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEHIGHLIGHTS);

        $routeComponents_userdetails = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
        );
        foreach ($routeComponents_userdetails as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_DETAILS) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userfullview = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_userfullview as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_FULLVIEW) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userthumbnail = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_userthumbnail as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_THUMBNAIL) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }
        $routeComponents_userlist = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::class, PoP_SocialNetwork_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST],
        );
        foreach ($routeComponents_userlist as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_LIST) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
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
