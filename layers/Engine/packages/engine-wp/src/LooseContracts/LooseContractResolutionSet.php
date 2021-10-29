<?php

declare(strict_types=1);

namespace PoP\EngineWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Actions
        // 1. Init comes before boot. We don't have the requested post/user/etc
        // parsed yet, so use with care
        $this->getHooksAPI()->addAction('init', function (): void {
            $this->getHooksAPI()->doAction('popcms:init');
        });
        // 2. Boot once it has parsed the WP_Query, so that the requested post/user/etc
        // is already processed and available. Only hook available is "wp"
        // Watch out: "wp" doesn't trigger in the admin()!
        // Hence, in that case, use "wp_loaded" instead
        $this->getHooksAPI()->addAction(\is_admin() ? 'wp_loaded' : 'wp', function (): void {
            $this->getHooksAPI()->doAction('popcms:boot');
        });
        $this->getHooksAPI()->addAction('shutdown', function (): void {
            $this->getHooksAPI()->doAction('popcms:shutdown');
        });
        $this->getHooksAPI()->addAction('activate_plugin', function (): void {
            $this->getHooksAPI()->doAction('popcms:componentInstalled');
            $this->getHooksAPI()->doAction('popcms:componentInstalledOrUninstalled');
        });
        $this->getHooksAPI()->addAction('deactivate_plugin', function (): void {
            $this->getHooksAPI()->doAction('popcms:componentUninstalled');
            $this->getHooksAPI()->doAction('popcms:componentInstalledOrUninstalled');
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
