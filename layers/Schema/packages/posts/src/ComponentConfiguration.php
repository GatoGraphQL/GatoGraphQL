<?php

declare(strict_types=1);

namespace PoPSchema\Posts;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getPostListDefaultLimit = 10;
    private ?int $getPostListMaxLimit = -1;
    private bool $addPostTypeToCustomPostUnionTypes = true;
    private string $getPostsRoute = '';

    public function getPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::POST_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getPostListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getPostListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::POST_LIST_MAX_LIMIT;
        $selfProperty = &$this->getPostListMaxLimit;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function addPostTypeToCustomPostUnionTypes(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_POST_TYPE_TO_CUSTOMPOST_UNION_TYPES;
        $selfProperty = &$this->addPostTypeToCustomPostUnionTypes;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getPostsRoute(): string
    {
        // Define properties
        $envVariable = Environment::POSTS_ROUTE;
        $selfProperty = &$this->getPostsRoute;
        $defaultValue = 'posts';

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
