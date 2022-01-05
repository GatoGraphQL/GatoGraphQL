<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private array $getCommentMetaEntries = [];
    private string $getCommentMetaBehavior = Behaviors::ALLOWLIST;

    public function getCommentMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::COMMENT_META_ENTRIES;
        $selfProperty = &$this->getCommentMetaEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getCommentMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::COMMENT_META_BEHAVIOR;
        $selfProperty = &$this->getCommentMetaBehavior;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
