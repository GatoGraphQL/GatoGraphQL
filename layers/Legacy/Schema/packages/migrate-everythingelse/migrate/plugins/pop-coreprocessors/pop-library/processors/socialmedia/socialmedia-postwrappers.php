<?php

class PoP_Module_Processor_SocialMediaPostWrappers extends PoP_Module_Processor_SocialMediaPostWrapperBase
{
    public final const MODULE_POSTSOCIALMEDIA_POSTWRAPPER = 'post-socialmedia-wrapper';
    public final const MODULE_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER = 'post-socialmedia-counter-wrapper';
    public final const MODULE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER = 'subjugatedpost-socialmedia-wrapper';
    public final const MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER = 'subjugatedpost-socialmedia-counter-wrapper';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_POSTSOCIALMEDIA_POSTWRAPPER],
            [self::class, self::MODULE_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER],
            [self::class, self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER],
            [self::class, self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER],
        );
    }

    public function getSocialmediaModule(array $module)
    {
        $socialmedias = array(
            self::MODULE_POSTSOCIALMEDIA_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::MODULE_POSTSOCIALMEDIA],
            self::MODULE_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::MODULE_POSTSOCIALMEDIA_COUNTER],
            self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::MODULE_SUBJUGATEDPOSTSOCIALMEDIA],
            self::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER => [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::MODULE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER],
        );

        if ($socialmedia = $socialmedias[$module[1]] ?? null) {
            return $socialmedia;
        }

        return parent::getSocialmediaModule($module);
    }
}


