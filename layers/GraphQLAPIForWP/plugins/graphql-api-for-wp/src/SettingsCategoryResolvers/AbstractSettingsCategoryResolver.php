<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers;

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

    /**
     * Convert "graphql-api-settings" into "graphql_api_settings" and like that
     */
    public function getOptionsFormName(string $settingsCategory): string
    {
        return str_replace('-', '_', $this->getDBOptionName($settingsCategory));
    }
}
