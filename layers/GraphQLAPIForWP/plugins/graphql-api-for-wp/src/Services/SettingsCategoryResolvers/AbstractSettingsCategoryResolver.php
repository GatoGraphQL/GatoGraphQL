<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SettingsCategoryResolvers;

use PoP\Root\Exception\ImpossibleToHappenException;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractSettingsCategoryResolver implements SettingsCategoryResolverInterface
{
    use BasicServiceTrait;

    public function getDescription(string $settingsCategory): ?string
    {
        return null;
    }

    public function getDBOptionName(string $settingsCategory): string
    {
        throw new ImpossibleToHappenException(
            sprintf(
                $this->__('Unsupported Settings Category \'%s\'', 'graphql-api'),
                $settingsCategory
            )
        );
    }
}
