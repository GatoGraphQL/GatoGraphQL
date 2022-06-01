<?php

class PoP_Module_Processor_SocialMediaPostWrappers extends PoP_Module_Processor_SocialMediaPostWrapperBase
{
    public final const COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER = 'post-socialmedia-wrapper';
    public final const COMPONENT_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER = 'post-socialmedia-counter-wrapper';
    public final const COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER = 'subjugatedpost-socialmedia-wrapper';
    public final const COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER = 'subjugatedpost-socialmedia-counter-wrapper';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER,
            self::COMPONENT_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER,
            self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER,
            self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER,
        );
    }

    public function getSocialmediaComponent(\PoP\ComponentModel\Component\Component $component)
    {
        $socialmedias = array(
            self::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_POSTSOCIALMEDIA],
            self::COMPONENT_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_POSTSOCIALMEDIA_COUNTER],
            self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA],
            self::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER],
        );

        if ($socialmedia = $socialmedias[$component->name] ?? null) {
            return $socialmedia;
        }

        return parent::getSocialmediaComponent($component);
    }
}


