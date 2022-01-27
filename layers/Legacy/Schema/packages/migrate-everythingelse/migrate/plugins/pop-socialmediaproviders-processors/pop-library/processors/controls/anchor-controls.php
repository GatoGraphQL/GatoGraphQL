<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_SocialMediaProviders_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_ANCHORCONTROL_SHARE_FACEBOOK = 'anchorcontrol-share-facebook';
    public const MODULE_ANCHORCONTROL_SHARE_TWITTER = 'anchorcontrol-share-twitter';
    public const MODULE_ANCHORCONTROL_SHARE_LINKEDIN = 'anchorcontrol-share-linkedin';
    public const MODULE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK = 'anchorcontrol-fixedshare-facebook';
    public const MODULE_ANCHORCONTROL_FIXEDSHARE_TWITTER = 'anchorcontrol-fixedshare-twitter';
    public const MODULE_ANCHORCONTROL_FIXEDSHARE_LINKEDIN = 'anchorcontrol-fixedshare-linkedin';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_SHARE_FACEBOOK],
            [self::class, self::MODULE_ANCHORCONTROL_SHARE_TWITTER],
            [self::class, self::MODULE_ANCHORCONTROL_SHARE_LINKEDIN],
            [self::class, self::MODULE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK],
            [self::class, self::MODULE_ANCHORCONTROL_FIXEDSHARE_TWITTER],
            [self::class, self::MODULE_ANCHORCONTROL_FIXEDSHARE_LINKEDIN],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_SHARE_FACEBOOK:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK:
                return TranslationAPIFacade::getInstance()->__('Facebook', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_SHARE_TWITTER:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_TWITTER:
                return TranslationAPIFacade::getInstance()->__('Twitter', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_SHARE_LINKEDIN:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_LINKEDIN:
                return TranslationAPIFacade::getInstance()->__('LinkedIn', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_SHARE_FACEBOOK:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK:
                return 'fa-facebook';

            case self::MODULE_ANCHORCONTROL_SHARE_TWITTER:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_TWITTER:
                return 'fa-twitter';

            case self::MODULE_ANCHORCONTROL_SHARE_LINKEDIN:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_LINKEDIN:
                return 'fa-linkedin';
        }

        return parent::getFontawesome($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $cmsService = CMSServiceFacade::getInstance();
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_SHARE_FACEBOOK:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK:
            case self::MODULE_ANCHORCONTROL_SHARE_TWITTER:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_TWITTER:
            case self::MODULE_ANCHORCONTROL_SHARE_LINKEDIN:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_LINKEDIN:
                $providers = array(
                    self::MODULE_ANCHORCONTROL_SHARE_FACEBOOK => GD_SOCIALMEDIA_PROVIDER_FACEBOOK,
                    self::MODULE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK => GD_SOCIALMEDIA_PROVIDER_FACEBOOK,
                    self::MODULE_ANCHORCONTROL_SHARE_TWITTER => GD_SOCIALMEDIA_PROVIDER_TWITTER,
                    self::MODULE_ANCHORCONTROL_FIXEDSHARE_TWITTER => GD_SOCIALMEDIA_PROVIDER_TWITTER,
                    self::MODULE_ANCHORCONTROL_SHARE_LINKEDIN => GD_SOCIALMEDIA_PROVIDER_LINKEDIN,
                    self::MODULE_ANCHORCONTROL_FIXEDSHARE_LINKEDIN => GD_SOCIALMEDIA_PROVIDER_LINKEDIN,
                );
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-provider' => $providers[$module[1]],
                        'data-blocktarget' => $this->getProp($module, $props, 'control-target')
                    )
                );

                $fixed = array(
                    [self::class, self::MODULE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK],
                    [self::class, self::MODULE_ANCHORCONTROL_FIXEDSHARE_TWITTER],
                    [self::class, self::MODULE_ANCHORCONTROL_FIXEDSHARE_LINKEDIN],
                );
                if (in_array($module, $fixed)) {
                      // Share the website URL
                    $title = sprintf(
                        '%s | %s',
                        $cmsapplicationapi->getSiteName(),
                        $cmsapplicationapi->getSiteDescription()
                    );
                    // Allow parent modules to override the share url and the title (eg: GetPoP Campaign)
                    $this->setProp($module, $props, 'title', $title);
                    $this->setProp($module, $props, 'shareURL', GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL()));
                    $this->mergeProp(
                        $module,
                        $props,
                        'params',
                        array(
                            'data-shareurl' => $this->getProp($module, $props, 'shareURL'),
                            'data-sharetitle' => str_replace(array('"', "'"), '', $this->getProp($module, $props, 'title')),
                        )
                    );
                }
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_SHARE_FACEBOOK:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK:
            case self::MODULE_ANCHORCONTROL_SHARE_TWITTER:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_TWITTER:
            case self::MODULE_ANCHORCONTROL_SHARE_LINKEDIN:
            case self::MODULE_ANCHORCONTROL_FIXEDSHARE_LINKEDIN:
                $this->addJsmethod($ret, 'controlSocialMedia');
                break;
        }
        return $ret;
    }
}


