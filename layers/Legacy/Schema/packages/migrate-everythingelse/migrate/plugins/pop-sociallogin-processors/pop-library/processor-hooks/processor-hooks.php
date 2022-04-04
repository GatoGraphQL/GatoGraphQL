<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_WSL_ProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_UserAccountUtils:login:modules',
            $this->loginchannels(...)
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_LoginGroups:props:hooks',
            $this->addHook(...)
        );
    }

    public function loginchannels($modules)
    {

        // Only if there are service providers
        if (!getSocialloginNetworklinks()) {
            return $modules;
        }

        // Add Facebook, Twitter, etc, after the Login Block
        array_splice(
            $modules, 
            array_search(
                [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGIN], 
                $modules
            )+1, 
            0, 
            array(
                [PoP_Module_Processor_SocialLoginElements::class, PoP_Module_Processor_SocialLoginElements::MODULE_SOCIALLOGIN_NETWORKLINKS],
            )
        );
        return $modules;
    }

    public function addHook($hooks)
    {

        // Only if there are service providers
        if (!getSocialloginNetworklinks()) {
            return $hooks;
        }

        $hooks[] = $this;
        return $hooks;
    }

    public function setModelProps(array $module, &$props, $processor)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        // Method called by the PoP_Module_Processor_LoginGroups::MODULE_GROUP_LOGIN processor to allow hooks to set $props
        // Make PoP_Module_Processor_LoginGroups::MODULE_GROUP_LOGIN the block to have a Disabled Layer on top
        // while waiting for the server authenticating the FB/Twitter user
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            $blocktarget = '#'.$processor->getFrontendId($module, $props);
            $processor->setProp([[PoP_Module_Processor_SocialLoginElements::class, PoP_Module_Processor_SocialLoginElements::MODULE_SOCIALLOGIN_NETWORKLINKS]], $props, 'loginblock-target', $blocktarget);
        }
        $processor->setProp(
            [
                [PoP_Module_Processor_SocialLoginElements::class, PoP_Module_Processor_SocialLoginElements::MODULE_SOCIALLOGIN_NETWORKLINKS],
            ],
            $props,
            'description',
            sprintf(
                '<p class="text-center"><em>%s</em></p>',
                TranslationAPIFacade::getInstance()->__('OR...', 'wsl-popprocessors')
            )
        );
        $processor->setProp(
            [
                [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGIN],
            ],
            $props,
            'description',
            sprintf(
                '<p><em>%s</em></p>',
                sprintf(
                    TranslationAPIFacade::getInstance()->__('Log in with your <strong>%s</strong> account:', 'wsl-popprocessors'),
                    $cmsapplicationapi->getSiteName()
                )
            )
        );
    }
}

/**
 * Initialization
 */
new GD_WSL_ProcessorHooks();
