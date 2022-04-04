<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserPlatform_Module_Processor_Blocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_USER_CHANGEPASSWORD = 'block-user-changepwd';
    public final const MODULE_BLOCK_MYPREFERENCES = 'block-mypreferences';
    public final const MODULE_BLOCK_INVITENEWUSERS = 'block-inviteusers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_USER_CHANGEPASSWORD],
            [self::class, self::MODULE_BLOCK_MYPREFERENCES],
            [self::class, self::MODULE_BLOCK_INVITENEWUSERS],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_INVITENEWUSERS => POP_USERPLATFORM_ROUTE_INVITENEWUSERS,
            self::MODULE_BLOCK_MYPREFERENCES => POP_USERPLATFORM_ROUTE_MYPREFERENCES,
            self::MODULE_BLOCK_USER_CHANGEPASSWORD => POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function showDisabledLayerIfCheckpointFailed(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYPREFERENCES:
                return true;
        }

        return parent::showDisabledLayerIfCheckpointFailed($module, $props);
        ;
    }

    protected function getDescription(array $module, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_INVITENEWUSERS:
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

        return parent::getDescription($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inner_modules = array(
            self::MODULE_BLOCK_USER_CHANGEPASSWORD => [PoP_UserPlatform_Module_Processor_Dataloads::class, PoP_UserPlatform_Module_Processor_Dataloads::MODULE_DATALOAD_USER_CHANGEPASSWORD],
            self::MODULE_BLOCK_MYPREFERENCES => [PoP_UserPlatform_Module_Processor_Dataloads::class, PoP_UserPlatform_Module_Processor_Dataloads::MODULE_DATALOAD_MYPREFERENCES],
            self::MODULE_BLOCK_INVITENEWUSERS => [PoP_UserPlatform_Module_Processor_Dataloads::class, PoP_UserPlatform_Module_Processor_Dataloads::MODULE_DATALOAD_INVITENEWUSERS],
        );

        if ($inner = $inner_modules[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }
}



