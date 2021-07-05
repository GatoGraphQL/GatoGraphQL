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
        $this->hooksAPI->addAction('init', function () {
            $this->hooksAPI->doAction('popcms:init');
        });
        // 2. Boot once it has parsed the WP_Query, so that the requested post/user/etc
        // is already processed and available. Only hook available is "wp"
        // Watch out: "wp" doesn't trigger in the admin()! Hence, in that case,
        // use "wp_loaded" instead
        $this->hooksAPI->addAction(\is_admin() ? 'wp_loaded' : 'wp', function () {
            $this->hooksAPI->doAction('popcms:boot');
        });
        $this->hooksAPI->addAction('shutdown', function () {
            $this->hooksAPI->doAction('popcms:shutdown');
        });
        $this->hooksAPI->addAction('activate_plugin', function () {
            $this->hooksAPI->doAction('popcms:componentInstalled');
            $this->hooksAPI->doAction('popcms:componentInstalledOrUninstalled');
        });
        $this->hooksAPI->addAction('deactivate_plugin', function () {
            $this->hooksAPI->doAction('popcms:componentUninstalled');
            $this->hooksAPI->doAction('popcms:componentInstalledOrUninstalled');
        });

        $this->looseContractManager->implementHooks([
            'popcms:init',
            'popcms:boot',
            'popcms:shutdown',
            'popcms:componentInstalled',
            'popcms:componentUninstalled',
            'popcms:componentInstalledOrUninstalled',
        ]);

        $this->nameResolver->implementNames([
            'popcms:option:dateFormat' => 'date_format',
            'popcms:option:charset' => 'blog_charset',
            'popcms:option:gmtOffset' => 'gmt_offset',
            'popcms:option:timezone' => 'timezone_string',
        ]);
    }
}
