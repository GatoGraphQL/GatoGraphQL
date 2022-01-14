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
        App::addAction('shutdown', function (): void {
            App::doAction('popcms:shutdown');
        });
        App::addAction('activate_plugin', function (): void {
            App::doAction('popcms:componentInstalledOrUninstalled');
        });
        App::addAction('deactivate_plugin', function (): void {
            App::doAction('popcms:componentInstalledOrUninstalled');
        });

        $this->getLooseContractManager()->implementHooks([
            'popcms:shutdown',
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
