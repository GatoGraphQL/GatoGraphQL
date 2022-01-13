<?php

declare(strict_types=1);

namespace PoPSchema\Posts;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getPostListDefaultLimit(): ?int
    {
        $envVariable = Environment::POST_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getPostListMaxLimit(): ?int
    {
        $envVariable = Environment::POST_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function addPostTypeToCustomPostUnionTypes(): bool
    {
        $envVariable = Environment::ADD_POST_TYPE_TO_CUSTOMPOST_UNION_TYPES;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getPostsRoute(): string
    {
        $envVariable = Environment::POSTS_ROUTE;
        $defaultValue = 'posts';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
