<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_FB],
            [self::class, self::COMPONENT_USERSOCIALMEDIA_FB],
            [self::class, self::COMPONENT_TAGSOCIALMEDIA_FB],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW],
            [self::class, self::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW],
            [self::class, self::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_TWITTER],
            [self::class, self::COMPONENT_USERSOCIALMEDIA_TWITTER],
            [self::class, self::COMPONENT_TAGSOCIALMEDIA_TWITTER],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW],
            [self::class, self::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW],
            [self::class, self::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN],
            [self::class, self::COMPONENT_USERSOCIALMEDIA_LINKEDIN],
            [self::class, self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW],
            [self::class, self::COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW],
            [self::class, self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW],
        );
    }

    public function getProvider(array $component)
    {
        switch ($component[1]) {
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

    public function getShareurlField(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_POSTSOCIALMEDIA_FB:
            case self::COMPONENT_USERSOCIALMEDIA_FB:
            case self::COMPONENT_TAGSOCIALMEDIA_FB:
            case self::COMPONENT_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_FB_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_FB_PREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('shareURL', ['provider' => 'facebook']);

            case self::COMPONENT_USERSOCIALMEDIA_TWITTER:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER_PREVIEW:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER_PREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('shareURL', ['provider' => 'twitter']);

            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('shareURL', ['provider' => 'linkedin']);
        }

        return parent::getTitleField($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
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
    public function getShortname(array $component)
    {
        switch ($component[1]) {
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
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
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

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_POSTSOCIALMEDIA_FB:
            case self::COMPONENT_USERSOCIALMEDIA_FB:
            case self::COMPONENT_TAGSOCIALMEDIA_FB:
            case self::COMPONENT_POSTSOCIALMEDIA_TWITTER:
            case self::COMPONENT_USERSOCIALMEDIA_TWITTER:
            case self::COMPONENT_TAGSOCIALMEDIA_TWITTER:
            case self::COMPONENT_POSTSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_USERSOCIALMEDIA_LINKEDIN:
            case self::COMPONENT_TAGSOCIALMEDIA_LINKEDIN:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($component, $props, 'resourceloader', 'socialmediaproviders');
                $this->appendProp($component, $props, 'class', 'socialmediaproviders-changebg icon-only');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


