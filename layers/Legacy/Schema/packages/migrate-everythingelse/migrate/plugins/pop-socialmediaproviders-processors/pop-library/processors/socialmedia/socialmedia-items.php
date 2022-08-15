<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_SocialMediaItems extends PoP_Module_Processor_SocialMediaItemsBase
{
    public final const COMPONENT_POSTSOCIALMEDIA_FB = 'post-socialmediaproviders-fb';
    public final const COMPONENT_TAGSOCIALMEDIA_FB = 'tag-socialmediaproviders-fb';
    public final const COMPONENT_USERSOCIALMEDIA_FB = 'user-socialmediaproviders-fb';
    public final const COMPONENT_POSTSOCIALMEDIA_TWITTER = 'post-socialmediaproviders-twitter';
    public final const COMPONENT_TAGSOCIALMEDIA_TWITTER = 'tag-socialmediaproviders-twitter';
    public final const COMPONENT_USERSOCIALMEDIA_TWITTER = 'user-socialmediaproviders-twitter';
    public final const COMPONENT_POSTSOCIALMEDIA_LINKEDIN = 'post-socialmediaproviders-linkedin';
    public final const COMPONENT_TAGSOCIALMEDIA_LINKEDIN = 'tag-socialmediaproviders-linkedin';
    public final const COMPONENT_USERSOCIALMEDIA_LINKEDIN = 'user-socialmediaproviders-linkedin';
    public final const COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW = 'post-socialmediaproviders-fb-preview';
    public final const COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW = 'tag-socialmediaproviders-fb-preview';
    public final const COMPONENT_USERSOCIALMEDIA_FB_PREVIEW = 'user-socialmediaproviders-fb-preview';
    public final const COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW = 'post-socialmediaproviders-twitter-preview';
    public final const COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW = 'tag-socialmediaproviders-twitter-preview';
    public final const COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW = 'user-socialmediaproviders-twitter-preview';
    public final const COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW = 'post-socialmediaproviders-linkedin-preview';
    public final const COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW = 'tag-socialmediaproviders-linkedin-preview';
    public final const COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW = 'user-socialmediaproviders-linkedin-preview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_POSTSOCIALMEDIA_FB,
            self::COMPONENT_USERSOCIALMEDIA_FB,
            self::COMPONENT_TAGSOCIALMEDIA_FB,
            self::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW,
            self::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW,
            self::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW,
            self::COMPONENT_POSTSOCIALMEDIA_TWITTER,
            self::COMPONENT_USERSOCIALMEDIA_TWITTER,
            self::COMPONENT_TAGSOCIALMEDIA_TWITTER,
            self::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW,
            self::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW,
            self::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW,
            self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN,
            self::COMPONENT_USERSOCIALMEDIA_LINKEDIN,
            self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN,
            self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW,
            self::COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW,
            self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW,
        );
    }

    public function getProvider(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_POSTSOCIALMEDIA_FB:
            case self::COMPONENT_USERSOCIALMEDIA_FB:
            case self::COMPONENT_TAGSOCIALMEDIA_FB:
            case self::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW:
                return GD_SOCIALMEDIA_PROVIDER_FACEBOOK;

            case self::COMPONENT_USERSOCIALMEDIA_TWITTER:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW:
                return GD_SOCIALMEDIA_PROVIDER_TWITTER;

            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return GD_SOCIALMEDIA_PROVIDER_LINKEDIN;
        }

        return parent::getProvider($component);
    }

    public function getShareurlField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_POSTSOCIALMEDIA_FB:
            case self::COMPONENT_USERSOCIALMEDIA_FB:
            case self::COMPONENT_TAGSOCIALMEDIA_FB:
            case self::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW:
                return /* @todo Re-do this code! Left undone */ new Field('shareURL', ['provider' => 'facebook']);

            case self::COMPONENT_USERSOCIALMEDIA_TWITTER:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW:
                return /* @todo Re-do this code! Left undone */ new Field('shareURL', ['provider' => 'twitter']);

            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return /* @todo Re-do this code! Left undone */ new Field('shareURL', ['provider' => 'linkedin']);
        }

        return parent::getTitleField($component);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_POSTSOCIALMEDIA_FB:
            case self::COMPONENT_USERSOCIALMEDIA_FB:
            case self::COMPONENT_TAGSOCIALMEDIA_FB:
            case self::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Facebook', 'pop-coreprocessors');

            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Twitter', 'pop-coreprocessors');

            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('LinkedIn', 'pop-coreprocessors');
        }

        return parent::getName($component);
    }
    public function getShortname(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_POSTSOCIALMEDIA_FB:
            case self::COMPONENT_USERSOCIALMEDIA_FB:
            case self::COMPONENT_TAGSOCIALMEDIA_FB:
            case self::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW:
                return 'facebook';

            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW:
                return 'twitter';


            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return 'linkedin';
        }

        return parent::getShortname($component);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_POSTSOCIALMEDIA_FB:
            case self::COMPONENT_USERSOCIALMEDIA_FB:
            case self::COMPONENT_TAGSOCIALMEDIA_FB:
                return 'fa-facebook fa-lg';

            case self::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW:
                return 'fa-facebook';

            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER:
                return 'fa-twitter fa-lg';

            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW:
                return 'fa-twitter';

            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN:
                return 'fa-linkedin fa-lg';

            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return 'fa-linkedin';
        }

        return parent::getFontawesome($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_POSTSOCIALMEDIA_FB:
            case self::COMPONENT_USERSOCIALMEDIA_FB:
            case self::COMPONENT_TAGSOCIALMEDIA_FB:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER:
            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'socialmediaproviders');
                $this->appendProp($component, $props, 'class', 'socialmediaproviders-changebg icon-only');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


