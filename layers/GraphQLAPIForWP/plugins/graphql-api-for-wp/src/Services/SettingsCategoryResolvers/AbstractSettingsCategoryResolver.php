<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SettingsCategoryResolvers;

abstract class AbstractSettingsCategoryResolver implements SettingsCategoryResolverInterface
{
    public function getDescription(string $settingsCategory): ?string
    {
        return null;
    }
}
