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
        App::addAction('init', function (): void {
            App::doAction('popcms:init');
        });
        App::addAction('shutdown', function (): void {
            App::doAction('popcms:shutdown');
        });
        App::addAction('activate_plugin', function (): void {
            App::doAction('popcms:componentInstalled');
            App::doAction('popcms:componentInstalledOrUninstalled');
        });
        App::addAction('deactivate_plugin', function (): void {
            App::doAction('popcms:componentUninstalled');
            App::doAction('popcms:componentInstalledOrUninstalled');
        });

        $this->getLooseContractManager()->implementHooks([
            'popcms:init',
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
