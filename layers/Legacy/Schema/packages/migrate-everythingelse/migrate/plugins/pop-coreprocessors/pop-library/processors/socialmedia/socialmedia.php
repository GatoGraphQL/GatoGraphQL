<?php

class PoP_Module_Processor_SocialMedia extends PoP_Module_Processor_SocialMediaBase
{
    public final const MODULE_POSTSOCIALMEDIA = 'post-socialmedia';
    public final const MODULE_POSTSOCIALMEDIA_COUNTER = 'post-socialmedia-counter';
    public final const MODULE_SUBJUGATEDPOSTSOCIALMEDIA = 'subjugatedpost-socialmedia';
    public final const MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER = 'subjugatedpost-socialmedia-counter';
    public final const MODULE_USERSOCIALMEDIA = 'user-socialmedia';
    public final const MODULE_USERSOCIALMEDIA_COUNTER = 'user-socialmedia-counter';
    public final const MODULE_TAGSOCIALMEDIA = 'tag-socialmedia';
    public final const MODULE_TAGSOCIALMEDIA_COUNTER = 'tag-socialmedia-counter';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_POSTSOCIALMEDIA],
            [self::class, self::MODULE_POSTSOCIALMEDIA_COUNTER],
            [self::class, self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA],
            [self::class, self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER],
            [self::class, self::MODULE_USERSOCIALMEDIA],
            [self::class, self::MODULE_USERSOCIALMEDIA_COUNTER],
            [self::class, self::MODULE_TAGSOCIALMEDIA],
            [self::class, self::MODULE_TAGSOCIALMEDIA_COUNTER],
        );
    }

    public function useCounter(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_POSTSOCIALMEDIA_COUNTER:
            case self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER:
            case self::MODULE_USERSOCIALMEDIA_COUNTER:
            case self::MODULE_TAGSOCIALMEDIA_COUNTER:
                return true;
        }
        return parent::useCounter($module);
    }

    public function getSubComponentVariations(array $module): array
    {
        switch ($module[1]) {
            case self::MODULE_POSTSOCIALMEDIA:
            case self::MODULE_POSTSOCIALMEDIA_COUNTER:
                return array(
                    [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTOPTIONS],
                );
        
            case self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA:
            case self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER:
                // Use the Up/Down vote instead of recomment. Needed for "subjugated" posts
                // Eg: highlights and stances
                return array(
                    [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTOPTIONS],
                );

            case self::MODULE_USERSOCIALMEDIA:
            case self::MODULE_USERSOCIALMEDIA_COUNTER:
                $ret = array();
                if (defined('POP_SOCIALNETWORKPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER];
                }
                $ret[] = [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_USEROPTIONS];
                return $ret;

            case self::MODULE_TAGSOCIALMEDIA:
            case self::MODULE_TAGSOCIALMEDIA_COUNTER:
                return array(
                    [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_TAGOPTIONS],
                );
        }
        
        return parent::getSubComponentVariations($module);
    }
}


