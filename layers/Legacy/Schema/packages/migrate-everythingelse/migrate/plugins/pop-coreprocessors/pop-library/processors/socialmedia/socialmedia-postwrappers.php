<?php

class PoP_Module_Processor_SocialMediaPostWrappers extends PoP_Module_Processor_SocialMediaPostWrapperBase
{
    public final const MODULE_POSTSOCIALMEDIA_POSTWRAPPER = 'post-socialmedia-wrapper';
    public final const MODULE_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER = 'post-socialmedia-counter-wrapper';
    public final const MODULE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER = 'subjugatedpost-socialmedia-wrapper';
    public final const MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER = 'subjugatedpost-socialmedia-counter-wrapper';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER],
            [self::class, self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER],
            [self::class, self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER],
        );
    }

    public function getSocialmediaModule(array $component)
    {
        $socialmedias = array(
            self::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_POSTSOCIALMEDIA],
            self::COMPONENT_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_POSTSOCIALMEDIA_COUNTER],
            self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA],
            self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER],
        );

        if ($socialmedia = $socialmedias[$component[1]] ?? null) {
            return $socialmedia;
        }

        return parent::getSocialmediaModule($component);
    }
}


