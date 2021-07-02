<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class EnvironmentVariablesDataSource
{
    /**
     * @var string
     */
    public const CHECKOUT_SUBMODULES = 'checkout-submodules';

    /**
     * @return array<string,string>
     */
    public function getEnvironmentVariables(): array
    {
        return [
            // [multi-monorepo] Env var overriden by downstream monorepo
            self::CHECKOUT_SUBMODULES => '',
        ];
    }
}
