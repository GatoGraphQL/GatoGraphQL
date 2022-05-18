<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_SocialMediaItems extends PoP_Module_Processor_SocialMediaItemsBase
{
    public final const MODULE_POSTSOCIALMEDIA_FB = 'post-socialmediaproviders-fb';
    public final const MODULE_TAGSOCIALMEDIA_FB = 'tag-socialmediaproviders-fb';
    public final const MODULE_USERSOCIALMEDIA_FB = 'user-socialmediaproviders-fb';
    public final const MODULE_POSTSOCIALMEDIA_TWITTER = 'post-socialmediaproviders-twitter';
    public final const MODULE_TAGSOCIALMEDIA_TWITTER = 'tag-socialmediaproviders-twitter';
    public final const MODULE_USERSOCIALMEDIA_TWITTER = 'user-socialmediaproviders-twitter';
    public final const MODULE_POSTSOCIALMEDIA_LINKEDIN = 'post-socialmediaproviders-linkedin';
    public final const MODULE_TAGSOCIALMEDIA_LINKEDIN = 'tag-socialmediaproviders-linkedin';
    public final const MODULE_USERSOCIALMEDIA_LINKEDIN = 'user-socialmediaproviders-linkedin';
    public final const MODULE_POSTSOCIALMEDIA_FB_PREVIEW = 'post-socialmediaproviders-fb-preview';
    public final const MODULE_TAGSOCIALMEDIA_FB_PREVIEW = 'tag-socialmediaproviders-fb-preview';
    public final const MODULE_USERSOCIALMEDIA_FB_PREVIEW = 'user-socialmediaproviders-fb-preview';
    public final const MODULE_POSTSOCIALMEDIA_TWITTER_PREVIEW = 'post-socialmediaproviders-twitter-preview';
    public final const MODULE_TAGSOCIALMEDIA_TWITTER_PREVIEW = 'tag-socialmediaproviders-twitter-preview';
    public final const MODULE_USERSOCIALMEDIA_TWITTER_PREVIEW = 'user-socialmediaproviders-twitter-preview';
    public final const MODULE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW = 'post-socialmediaproviders-linkedin-preview';
    public final const MODULE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW = 'tag-socialmediaproviders-linkedin-preview';
    public final const MODULE_USERSOCIALMEDIA_LINKEDIN_PREVIEW = 'user-socialmediaproviders-linkedin-preview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_POSTSOCIALMEDIA_FB],
            [self::class, self::MODULE_USERSOCIALMEDIA_FB],
            [self::class, self::MODULE_TAGSOCIALMEDIA_FB],
            [self::class, self::MODULE_POSTSOCIALMEDIA_FB_PREVIEW],
            [self::class, self::MODULE_USERSOCIALMEDIA_FB_PREVIEW],
            [self::class, self::MODULE_TAGSOCIALMEDIA_FB_PREVIEW],
            [self::class, self::MODULE_POSTSOCIALMEDIA_TWITTER],
            [self::class, self::MODULE_USERSOCIALMEDIA_TWITTER],
            [self::class, self::MODULE_TAGSOCIALMEDIA_TWITTER],
            [self::class, self::MODULE_POSTSOCIALMEDIA_TWITTER_PREVIEW],
            [self::class, self::MODULE_USERSOCIALMEDIA_TWITTER_PREVIEW],
            [self::class, self::MODULE_TAGSOCIALMEDIA_TWITTER_PREVIEW],
            [self::class, self::MODULE_POSTSOCIALMEDIA_LINKEDIN],
            [self::class, self::MODULE_USERSOCIALMEDIA_LINKEDIN],
            [self::class, self::MODULE_TAGSOCIALMEDIA_LINKEDIN],
            [self::class, self::MODULE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW],
            [self::class, self::MODULE_USERSOCIALMEDIA_LINKEDIN_PREVIEW],
            [self::class, self::MODULE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW],
        );
    }

    public function getProvider(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_POSTSOCIALMEDIA_FB:
            case self::MODULE_USERSOCIALMEDIA_FB:
            case self::MODULE_TAGSOCIALMEDIA_FB:
            case self::MODULE_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_FB_PREVIEW:
                return GD_SOCIALMEDIA_PROVIDER_FACEBOOK;

            case self::MODULE_USERSOCIALMEDIA_TWITTER:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER:
            case self::MODULE_POSTSOCIALMEDIA_TWITTER:
            case self::MODULE_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
                return GD_SOCIALMEDIA_PROVIDER_TWITTER;

            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN:
            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return GD_SOCIALMEDIA_PROVIDER_LINKEDIN;
        }

        return parent::getProvider($componentVariation);
    }

    public function getShareurlField(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_POSTSOCIALMEDIA_FB:
            case self::MODULE_USERSOCIALMEDIA_FB:
            case self::MODULE_TAGSOCIALMEDIA_FB:
            case self::MODULE_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_FB_PREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('shareURL', ['provider' => 'facebook']);

            case self::MODULE_USERSOCIALMEDIA_TWITTER:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER:
            case self::MODULE_POSTSOCIALMEDIA_TWITTER:
            case self::MODULE_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('shareURL', ['provider' => 'twitter']);

            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN:
            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('shareURL', ['provider' => 'linkedin']);
        }

        return parent::getTitleField($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_POSTSOCIALMEDIA_FB:
            case self::MODULE_USERSOCIALMEDIA_FB:
            case self::MODULE_TAGSOCIALMEDIA_FB:
            case self::MODULE_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_FB_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Facebook', 'pop-coreprocessors');

            case self::MODULE_POSTSOCIALMEDIA_TWITTER:
            case self::MODULE_USERSOCIALMEDIA_TWITTER:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER:
            case self::MODULE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Twitter', 'pop-coreprocessors');

            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN:
            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('LinkedIn', 'pop-coreprocessors');
        }

        return parent::getName($componentVariation);
    }
    public function getShortname(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_POSTSOCIALMEDIA_FB:
            case self::MODULE_USERSOCIALMEDIA_FB:
            case self::MODULE_TAGSOCIALMEDIA_FB:
            case self::MODULE_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_FB_PREVIEW:
                return 'facebook';

            case self::MODULE_POSTSOCIALMEDIA_TWITTER:
            case self::MODULE_USERSOCIALMEDIA_TWITTER:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER:
            case self::MODULE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER_PREVIEW:
                return 'twitter';


            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN:
            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return 'linkedin';
        }

        return parent::getShortname($componentVariation);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_POSTSOCIALMEDIA_FB:
            case self::MODULE_USERSOCIALMEDIA_FB:
            case self::MODULE_TAGSOCIALMEDIA_FB:
                return 'fa-facebook fa-lg';

            case self::MODULE_POSTSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_FB_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_FB_PREVIEW:
                return 'fa-facebook';

            case self::MODULE_POSTSOCIALMEDIA_TWITTER:
            case self::MODULE_USERSOCIALMEDIA_TWITTER:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER:
                return 'fa-twitter fa-lg';

            case self::MODULE_POSTSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_TWITTER_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER_PREVIEW:
                return 'fa-twitter';

            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN:
                return 'fa-linkedin fa-lg';

            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN_PREVIEW:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN_PREVIEW:
                return 'fa-linkedin';
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_POSTSOCIALMEDIA_FB:
            case self::MODULE_USERSOCIALMEDIA_FB:
            case self::MODULE_TAGSOCIALMEDIA_FB:
            case self::MODULE_POSTSOCIALMEDIA_TWITTER:
            case self::MODULE_USERSOCIALMEDIA_TWITTER:
            case self::MODULE_TAGSOCIALMEDIA_TWITTER:
            case self::MODULE_POSTSOCIALMEDIA_LINKEDIN:
            case self::MODULE_USERSOCIALMEDIA_LINKEDIN:
            case self::MODULE_TAGSOCIALMEDIA_LINKEDIN:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($componentVariation, $props, 'resourceloader', 'socialmediaproviders');
                $this->appendProp($componentVariation, $props, 'class', 'socialmediaproviders-changebg icon-only');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


