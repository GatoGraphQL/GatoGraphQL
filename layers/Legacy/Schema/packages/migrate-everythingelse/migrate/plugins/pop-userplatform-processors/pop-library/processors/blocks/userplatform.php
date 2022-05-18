<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserPlatform_Module_Processor_Blocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_USER_CHANGEPASSWORD = 'block-user-changepwd';
    public final const COMPONENT_BLOCK_MYPREFERENCES = 'block-mypreferences';
    public final const COMPONENT_BLOCK_INVITENEWUSERS = 'block-inviteusers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_USER_CHANGEPASSWORD],
            [self::class, self::COMPONENT_BLOCK_MYPREFERENCES],
            [self::class, self::COMPONENT_BLOCK_INVITENEWUSERS],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_INVITENEWUSERS => POP_USERPLATFORM_ROUTE_INVITENEWUSERS,
            self::COMPONENT_BLOCK_MYPREFERENCES => POP_USERPLATFORM_ROUTE_MYPREFERENCES,
            self::COMPONENT_BLOCK_USER_CHANGEPASSWORD => POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function showDisabledLayerIfCheckpointFailed(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_MYPREFERENCES:
                return true;
        }

        return parent::showDisabledLayerIfCheckpointFailed($component, $props);
        ;
    }

    protected function getDescription(array $component, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_INVITENEWUSERS:
                // Allow Organik Fundraising to override it, changing the title to "Share by email"
                return \PoP\Root\App::applyFilters(
                    'GD_Core_Module_Processor_Blocks:inviteusers:description',
                    sprintf(
                        '<p>%s</p>',
                        sprintf(
                            TranslationAPIFacade::getInstance()->__('Send an invite to your friends to join <em><strong>%s</strong></em>:', 'pop-coreprocessors'),
                            $cmsapplicationapi->getSiteName()
                        )
                    )
                );
        }

        return parent::getDescription($component, $props);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inner_components = array(
            self::COMPONENT_BLOCK_USER_CHANGEPASSWORD => [PoP_UserPlatform_Module_Processor_Dataloads::class, PoP_UserPlatform_Module_Processor_Dataloads::COMPONENT_DATALOAD_USER_CHANGEPASSWORD],
            self::COMPONENT_BLOCK_MYPREFERENCES => [PoP_UserPlatform_Module_Processor_Dataloads::class, PoP_UserPlatform_Module_Processor_Dataloads::COMPONENT_DATALOAD_MYPREFERENCES],
            self::COMPONENT_BLOCK_INVITENEWUSERS => [PoP_UserPlatform_Module_Processor_Dataloads::class, PoP_UserPlatform_Module_Processor_Dataloads::COMPONENT_DATALOAD_INVITENEWUSERS],
        );

        if ($inner = $inner_components[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }
}



