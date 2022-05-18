<?php

class PoP_SocialMediaProviders_CoreProcessors_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonControls:modules:share',
            $this->getShareSubmodules(...),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            $this->getDropdownSubmodules(...),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_SocialMediaMultipleComponents:modules',
            $this->getSocialmediaprovidersSubmodules(...),
            10,
            2
        );
    }

    public function getShareSubmodules($subComponents, array $component)
    {
        // Insert at the beginning
        array_splice(
            $subComponents, 
            0, 
            0, 
            array(
                [GD_SocialMediaProviders_Module_Processor_AnchorControls::class, GD_SocialMediaProviders_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_SHARE_FACEBOOK],
                [GD_SocialMediaProviders_Module_Processor_AnchorControls::class, GD_SocialMediaProviders_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_SHARE_TWITTER],
            )
        );
        return $subComponents;
    }

    public function getDropdownSubmodules($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                array_splice(
                    $subComponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_POSTSOCIALMEDIA_FB_PREVIEW],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_POSTSOCIALMEDIA_TWITTER_PREVIEW],
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                array_splice(
                    $subComponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_USERSOCIALMEDIA_FB_PREVIEW],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_USERSOCIALMEDIA_TWITTER_PREVIEW],
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                array_splice(
                    $subComponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_TAGSOCIALMEDIA_FB_PREVIEW],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_TAGSOCIALMEDIA_TWITTER_PREVIEW],
                    )
                );
                break;
        }
        
        return $subComponents;
    }

    public function getSocialmediaprovidersSubmodules($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTSOCIALMEDIA:
                array_splice(
                    $subComponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_POSTSOCIALMEDIA_FB],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_POSTSOCIALMEDIA_TWITTER],
                    )
                );
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_USERSOCIALMEDIA:
                array_splice(
                    $subComponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_USERSOCIALMEDIA_FB],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_USERSOCIALMEDIA_TWITTER],
                    )
                );
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_TAGSOCIALMEDIA:
                array_splice(
                    $subComponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_TAGSOCIALMEDIA_FB],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::MODULE_TAGSOCIALMEDIA_TWITTER],
                    )
                );
                break;
        }

        return $subComponents;
    }
}

/**
 * Initialization
 */
new PoP_SocialMediaProviders_CoreProcessors_Hooks();
