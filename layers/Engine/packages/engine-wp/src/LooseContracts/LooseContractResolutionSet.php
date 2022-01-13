<?php

declare(strict_types=1);

namespace PoP\EngineWP\LooseContracts;

use PoP\Root\App;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Actions
        // 1. Init comes before boot. We don't have the requested post/user/etc
        // parsed yet, so use with care
        App::getHookManager()->addAction('init', function (): void {
            App::getHookManager()->doAction('popcms:init');
        });
        // 2. Boot once it has parsed the WP_Query, so that the requested post/user/etc
        // is already processed and available. Only hook available is "wp"
        // Watch out: "wp" doesn't trigger in the admin()!
        // Hence, in that case, use "wp_loaded" instead
        App::getHookManager()->addAction(\is_admin() ? 'wp_loaded' : 'wp', function (): void {
            App::getHookManager()->doAction('popcms:boot');
        });
        App::getHookManager()->addAction('shutdown', function (): void {
            App::getHookManager()->doAction('popcms:shutdown');
        });
        App::getHookManager()->addAction('activate_plugin', function (): void {
            App::getHookManager()->doAction('popcms:componentInstalled');
            App::getHookManager()->doAction('popcms:componentInstalledOrUninstalled');
        });
        App::getHookManager()->addAction('deactivate_plugin', function (): void {
            App::getHookManager()->doAction('popcms:componentUninstalled');
            App::getHookManager()->doAction('popcms:componentInstalledOrUninstalled');
        });

        $this->getLooseContractManager()->implementHooks([
            'popcms:init',
            'popcms:boot',
            'popcms:shutdown',
            'popcms:componentInstalled',
            'popcms:componentUninstalled',
            'popcms:componentInstalledOrUninstalled',
        ]);

        $this->getNameResolver()->implementNames([
            'popcms:option:dateFormat' => 'date_format',
            'popcms:option:charset' => 'blog_charset',
            'popcms:option:gmtOffset' => 'gmt_offset',
            'popcms:option:timezone' => 'timezone_string',
        ]);
    }
}
