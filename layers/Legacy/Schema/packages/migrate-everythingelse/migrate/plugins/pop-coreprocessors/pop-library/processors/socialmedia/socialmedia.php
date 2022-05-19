<?php

class PoP_Module_Processor_SocialMedia extends PoP_Module_Processor_SocialMediaBase
{
    public final const COMPONENT_POSTSOCIALMEDIA = 'post-socialmedia';
    public final const COMPONENT_POSTSOCIALMEDIA_COUNTER = 'post-socialmedia-counter';
    public final const COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA = 'subjugatedpost-socialmedia';
    public final const COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER = 'subjugatedpost-socialmedia-counter';
    public final const COMPONENT_USERSOCIALMEDIA = 'user-socialmedia';
    public final const COMPONENT_USERSOCIALMEDIA_COUNTER = 'user-socialmedia-counter';
    public final const COMPONENT_TAGSOCIALMEDIA = 'tag-socialmedia';
    public final const COMPONENT_TAGSOCIALMEDIA_COUNTER = 'tag-socialmedia-counter';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_POSTSOCIALMEDIA],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_COUNTER],
            [self::class, self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA],
            [self::class, self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER],
            [self::class, self::COMPONENT_USERSOCIALMEDIA],
            [self::class, self::COMPONENT_USERSOCIALMEDIA_COUNTER],
            [self::class, self::COMPONENT_TAGSOCIALMEDIA],
            [self::class, self::COMPONENT_TAGSOCIALMEDIA_COUNTER],
        );
    }

    public function useCounter(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_POSTSOCIALMEDIA_COUNTER:
            case self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER:
            case self::COMPONENT_USERSOCIALMEDIA_COUNTER:
            case self::COMPONENT_TAGSOCIALMEDIA_COUNTER:
                return true;
        }
        return parent::useCounter($component);
    }

    public function getSubcomponents(array $component): array
    {
        switch ($component[1]) {
            case self::COMPONENT_POSTSOCIALMEDIA:
            case self::COMPONENT_POSTSOCIALMEDIA_COUNTER:
                return array(
                    [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTOPTIONS],
                );
        
            case self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA:
            case self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER:
                // Use the Up/Down vote instead of recomment. Needed for "subjugated" posts
                // Eg: highlights and stances
                return array(
                    [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTOPTIONS],
                );

            case self::COMPONENT_USERSOCIALMEDIA:
            case self::COMPONENT_USERSOCIALMEDIA_COUNTER:
                $ret = array();
                if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER];
                }
                $ret[] = [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_USEROPTIONS];
                return $ret;

            case self::COMPONENT_TAGSOCIALMEDIA:
            case self::COMPONENT_TAGSOCIALMEDIA_COUNTER:
                return array(
                    [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_TAGOPTIONS],
                );
        }
        
        return parent::getSubcomponents($component);
    }
}


