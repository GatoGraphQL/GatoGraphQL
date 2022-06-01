<?php

class PoP_SocialMediaProviders_CoreProcessors_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonControls:components:share',
            $this->getShareSubcomponents(...),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            $this->getDropdownSubcomponents(...),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_SocialMediaMultipleComponents:modules',
            $this->getSocialmediaprovidersSubcomponents(...),
            10,
            2
        );
    }

    public function getShareSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
    {
        // Insert at the beginning
        array_splice(
            $subcomponents, 
            0, 
            0, 
            array(
                [GD_SocialMediaProviders_Module_Processor_AnchorControls::class, GD_SocialMediaProviders_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_SHARE_FACEBOOK],
                [GD_SocialMediaProviders_Module_Processor_AnchorControls::class, GD_SocialMediaProviders_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_SHARE_TWITTER],
            )
        );
        return $subcomponents;
    }

    /**
     * @param \PoP\ComponentModel\Component\Component[] $subcomponents
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getDropdownSubcomponents(array $subcomponents, \PoP\ComponentModel\Component\Component $component): array
    {
        switch ($component->name) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                array_splice(
                    $subcomponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW],
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                array_splice(
                    $subcomponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW],
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                array_splice(
                    $subcomponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW],
                    )
                );
                break;
        }
        
        return $subcomponents;
    }

    public function getSocialmediaprovidersSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTSOCIALMEDIA:
                array_splice(
                    $subcomponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_POSTSOCIALMEDIA_FB],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_POSTSOCIALMEDIA_TWITTER],
                    )
                );
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_USERSOCIALMEDIA:
                array_splice(
                    $subcomponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_USERSOCIALMEDIA_FB],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_USERSOCIALMEDIA_TWITTER],
                    )
                );
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_TAGSOCIALMEDIA:
                array_splice(
                    $subcomponents, 
                    0, 
                    0, 
                    array(
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_TAGSOCIALMEDIA_FB],
                        [PoP_Module_Processor_SocialMediaItems::class, PoP_Module_Processor_SocialMediaItems::COMPONENT_TAGSOCIALMEDIA_TWITTER],
                    )
                );
                break;
        }

        return $subcomponents;
    }
}

/**
 * Initialization
 */
new PoP_SocialMediaProviders_CoreProcessors_Hooks();
