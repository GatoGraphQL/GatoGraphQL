<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_SocialMediaProviders_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_ANCHORCONTROL_SHARE_FACEBOOK = 'anchorcontrol-share-facebook';
    public final const COMPONENT_ANCHORCONTROL_SHARE_TWITTER = 'anchorcontrol-share-twitter';
    public final const COMPONENT_ANCHORCONTROL_SHARE_LINKEDIN = 'anchorcontrol-share-linkedin';
    public final const COMPONENT_ANCHORCONTROL_FIXEDSHARE_FACEBOOK = 'anchorcontrol-fixedshare-facebook';
    public final const COMPONENT_ANCHORCONTROL_FIXEDSHARE_TWITTER = 'anchorcontrol-fixedshare-twitter';
    public final const COMPONENT_ANCHORCONTROL_FIXEDSHARE_LINKEDIN = 'anchorcontrol-fixedshare-linkedin';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_ANCHORCONTROL_SHARE_FACEBOOK],
            [self::class, self::COMPONENT_ANCHORCONTROL_SHARE_TWITTER],
            [self::class, self::COMPONENT_ANCHORCONTROL_SHARE_LINKEDIN],
            [self::class, self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_FACEBOOK],
            [self::class, self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_TWITTER],
            [self::class, self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_LINKEDIN],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_SHARE_FACEBOOK:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_FACEBOOK:
                return TranslationAPIFacade::getInstance()->__('Facebook', 'pop-coreprocessors');

            case self::COMPONENT_ANCHORCONTROL_SHARE_TWITTER:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_TWITTER:
                return TranslationAPIFacade::getInstance()->__('Twitter', 'pop-coreprocessors');

            case self::COMPONENT_ANCHORCONTROL_SHARE_LINKEDIN:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_LINKEDIN:
                return TranslationAPIFacade::getInstance()->__('LinkedIn', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_SHARE_FACEBOOK:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_FACEBOOK:
                return 'fa-facebook';

            case self::COMPONENT_ANCHORCONTROL_SHARE_TWITTER:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_TWITTER:
                return 'fa-twitter';

            case self::COMPONENT_ANCHORCONTROL_SHARE_LINKEDIN:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_LINKEDIN:
                return 'fa-linkedin';
        }

        return parent::getFontawesome($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $cmsService = CMSServiceFacade::getInstance();
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_SHARE_FACEBOOK:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_FACEBOOK:
            case self::COMPONENT_ANCHORCONTROL_SHARE_TWITTER:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_TWITTER:
            case self::COMPONENT_ANCHORCONTROL_SHARE_LINKEDIN:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_LINKEDIN:
                $providers = array(
                    self::COMPONENT_ANCHORCONTROL_SHARE_FACEBOOK => GD_SOCIALMEDIA_PROVIDER_FACEBOOK,
                    self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_FACEBOOK => GD_SOCIALMEDIA_PROVIDER_FACEBOOK,
                    self::COMPONENT_ANCHORCONTROL_SHARE_TWITTER => GD_SOCIALMEDIA_PROVIDER_TWITTER,
                    self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_TWITTER => GD_SOCIALMEDIA_PROVIDER_TWITTER,
                    self::COMPONENT_ANCHORCONTROL_SHARE_LINKEDIN => GD_SOCIALMEDIA_PROVIDER_LINKEDIN,
                    self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_LINKEDIN => GD_SOCIALMEDIA_PROVIDER_LINKEDIN,
                );
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-provider' => $providers[$component[1]],
                        'data-blocktarget' => $this->getProp($component, $props, 'control-target')
                    )
                );

                $fixed = array(
                    [self::class, self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_FACEBOOK],
                    [self::class, self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_TWITTER],
                    [self::class, self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_LINKEDIN],
                );
                if (in_array($component, $fixed)) {
                      // Share the website URL
                    $title = sprintf(
                        '%s | %s',
                        $cmsapplicationapi->getSiteName(),
                        $cmsapplicationapi->getSiteDescription()
                    );
                    // Allow parent modules to override the share url and the title (eg: GetPoP Campaign)
                    $this->setProp($component, $props, 'title', $title);
                    $this->setProp($component, $props, 'shareURL', GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL()));
                    $this->mergeProp(
                        $component,
                        $props,
                        'params',
                        array(
                            'data-shareurl' => $this->getProp($component, $props, 'shareURL'),
                            'data-sharetitle' => str_replace(array('"', "'"), '', $this->getProp($component, $props, 'title')),
                        )
                    );
                }
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_SHARE_FACEBOOK:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_FACEBOOK:
            case self::COMPONENT_ANCHORCONTROL_SHARE_TWITTER:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_TWITTER:
            case self::COMPONENT_ANCHORCONTROL_SHARE_LINKEDIN:
            case self::COMPONENT_ANCHORCONTROL_FIXEDSHARE_LINKEDIN:
                $this->addJsmethod($ret, 'controlSocialMedia');
                break;
        }
        return $ret;
    }
}


